<?php
error_reporting(E_ALL);
include ("inc.php"); 
mysql_connect($sql_host, $sql_user, $sql_password) or
    die("No connection " . mysql_error());    
mysql_select_db($sql_db );


function number_pad($number,$n) 
{
return str_pad((int) $number,$n,"0",STR_PAD_LEFT);
}


function order_send($order_id,$host,$user,$pass,$dir,$db_index) 
{

global $gardners_folder;
$cur_dir =$gardners_folder;
$conn_id = ftp_connect($host,21) or die ("Cannot connect to host".$host);
 
// send access parameters
ftp_login($conn_id, $user, $pass) or die("Cannot login");
 
// turn on passive mode transfers (some servers need this)
ftp_pasv ($conn_id, true);
 
// perform file upload
$upload = ftp_put($conn_id, $dir.$order_id.".ORD", $cur_dir.'/'.$order_id.".ORD", FTP_ASCII);

if($upload)
{ 
echo 'Upload complete';

$try=1;
while((!rename($cur_dir.'/'.$order_id.".ORD", $cur_dir."/sent_orders/".$order_id.".ORD")) && ($try <6))
{
$try++;
echo 'Move file attempt!';
}
mysql_query("UPDATE ".$db_index."orders SET g_status=\"sent\" WHERE orderid=".$order_id) or die(mysql_error());

}
else
{
echo 'Cannot upload';
}


}

function split_words($string, $max = 1) 
{ 
    $words = preg_split('/\s/', $string); 
    $lines = array(); 
    $line = ''; 
    
    foreach ($words as $k => $word) { 
        $length = strlen($line . ' ' . $word); 
        if ($length <= $max) { 
            $line .= ' ' . $word; 
        } else if ($length > $max) { 
            if (!empty($line)) $lines[] = trim($line); 
            $line = $word; 
        } else { 
            $lines[] = trim($line) . ' ' . $word; 
            $line = ''; 
        } 
    } 
    $lines[] = ($line = trim($line)) ? $line : $word; 

    return $lines; 
}

 
function get_address($order_id,$db_index)
{
$sqla=mysql_query("SELECT b_address,b_city,b_county,b_state,b_country,b_zipcode FROM ".$db_index."orders WHERE orderid=".$order_id);
$result=mysql_fetch_assoc($sqla);
$address=$result['b_address'].','.$result['b_city'].','.$result['b_county'].','.$result['b_state'].','.$result['b_country'].','.$result['b_zipcode'];
return $address;
}

function get_non_downloadable($order_id,$db_index)
{
//i'll have to remove the software as well so



$sqla=mysql_query("SELECT productid FROM ".$db_index."order_details WHERE orderid=".$order_id);


while ($result=mysql_fetch_assoc($sqla)) {

    $product_arr[]=$result['productid'];
}
//print_r($product_arr);

foreach ($product_arr as $prod)
{
$is_software=false;
$sqlc=mysql_query("SELECT distribution FROM ".$db_index."products WHERE productid=".$prod);
$res=mysql_fetch_array($sqlc);
$sqlb=mysql_query("SELECT productid FROM ".$db_index."extra_field_values WHERE TRIM(value)='Software' AND fieldid='18' AND productid='".$prod."'");
if($row=mysql_fetch_assoc($sqlb))
$is_software=true;
//echo $prod.'distribution-x'.$res['distribution'].'x; is software-x';
if(($res['distribution']=='') && (!$is_software))
$final_arr[]=$prod;
}


return $final_arr;
}




function get_bundle_pack_products($prod_id,$db_index)
{

$sqla=mysql_query("SELECT fieldid FROM ".$db_index."extra_fields WHERE field='Bundle pack content'");
$result=mysql_fetch_assoc($sqla);
$bundle_content_field=$result['fieldid'];
//echo $bundle_content_field;
$sqla=mysql_query("SELECT fieldid FROM ".$db_index."extra_fields WHERE field='ISBN-13'");
$result=mysql_fetch_assoc($sqla);
$isbn_field=$result['fieldid'];



$sql = mysql_query("SELECT value FROM ".$db_index."extra_field_values WHERE fieldid=".$bundle_content_field." AND productid=".$prod_id);
$result=mysql_fetch_assoc($sql);
$isbns=$result['value'];


$isbn_arr=explode(";", trim($isbns));
$last=sizeof($isbn_arr)-1;
unset($isbn_arr[$last]);
$prod_id_arr=array();
foreach ($isbn_arr as $item)
{
$sql = mysql_query("SELECT productid FROM ".$db_index."extra_field_values WHERE fieldid=".$isbn_field." AND TRIM(value)=".$item);
$result=mysql_fetch_assoc($sql);

$prod_id_arr[]=$result['productid'];
}

//print_r($prod_id_arr);
return $prod_id_arr;
}







function get_bundled($order_id,$prod_id,$db_index,$position)
{

$sqla=mysql_query("SELECT fieldid FROM ".$db_index."extra_fields WHERE field='Bundled'");
$result=mysql_fetch_assoc($sqla);
$bundle_field=$result['fieldid'];



$bundle_pack_prod_array=array();


$sql = mysql_query("SELECT productid FROM ".$db_index."extra_field_values WHERE LCASE(TRIM(value)) = 'yes' AND fieldid=".$bundle_field." AND productid=".$prod_id);
$result=mysql_fetch_assoc($sql);

if(!empty($result))
{

$bundle_pack_prod_array=get_bundle_pack_products($prod_id,$db_index);

//get the product detail array

$sqla=mysql_query("SELECT * FROM ".$db_index."orders WHERE orderid=".$order_id);
$order_details=mysql_fetch_array($sqla);

$sqlk=mysql_query("SELECT s_address,s_city,s_county,s_state,s_country,s_zipcode,total,shipping_cost,shipping FROM ".$db_index."orders WHERE orderid=".$order_id);
$result=mysql_fetch_assoc($sqlk);

$price=$result['total']*100;
$delivery=$result['shipping_cost']*100;

$index=0;

foreach($bundle_pack_prod_array as $bundle)
{

$prod_det_array[$index]['prod_id']=$bundle;


//$prod_det_array[$index]['additional_reference']=str_replace('-','',$order_details['refrence_number']);
$prod_det_array[$index]['order_id']=$order_details['orderid'];

$sqla=mysql_query("SELECT value FROM ".$db_index."extra_field_values WHERE fieldid=10 AND productid=".$bundle);
$result=@mysql_fetch_assoc($sqla);

$prod_det_array[$index]['isbn']=$result['value'];


$sqlb=mysql_query("SELECT amount FROM ".$db_index."order_details WHERE orderid=".$order_id." AND productid=".$prod_id);
$result=mysql_fetch_assoc($sqlb);
$prod_det_array[$index]['quantity']=$result['amount'];

$prod_det_array[$index]['gardnersref']='0';
$gardnersref='0';
if($position != 0)
{

$prod_det_array[$index]['price']=0;
$prod_det_array[$index]['delivery']=0;
}
else
{
$prod_det_array[$index]['price']=$price;
$prod_det_array[$index]['delivery']=$delivery;
}

$index++;

}



}
//print_r($prod_det_array);

return $prod_det_array;
}


function remove_duplicate_prod($arr)
{
$till=sizeof($arr);
for($j=0;$j<$till;$j++)
{

$from=$j+1;
for($i=$from;$i<$till;$i++)
{
if($arr[$j]['prod_id']==$arr[$i]['prod_id'])
{
$arr[$j]['quantity']=$arr[$j]['quantity']+$arr[$i]['quantity'];

unset($arr[$i]);
}

}

}
$fin_arr=array();
foreach ($arr as $item)
{
if($item['prod_id'])
$fin_arr[]=$item;
}
return $fin_arr;
}

function create_order_file($order_id,$db_index)
{

$sqla=mysql_query("SELECT * FROM ".$db_index."orders WHERE orderid=".$order_id);
$order_details=mysql_fetch_array($sqla);

$sqlb=mysql_query("SELECT unique_reference FROM ".$db_index."gardners ORDER BY unique_reference DESC");
$last_unique_reference_a=mysql_fetch_array($sqlb);
$last_unique_reference=$last_unique_reference_a['unique_reference'];

//header
//echo $last_unique_reference;
$account="TEC004";
$date=date("d/m/Y");
$testing="N"; 
$sequence=$order_id;
$header='"HEADER"'.',"'.$account.'","'.$date.'","'.$testing.'","'.$sequence.'"'."\r\n";

//Home Delivery information fields

$sqle=mysql_query("SELECT title FROM ".$db_index."orders WHERE orderid=".$order_id);
$result=mysql_fetch_assoc($sqle);
$titlename=$result['title'];

$initials='';

$sqlf=mysql_query("SELECT lastname, firstname FROM ".$db_index."orders WHERE orderid=".$order_id);
$result=mysql_fetch_assoc($sqlf);
$iname=$result['firstname'].' '.$result['lastname'];
 

/*$address=get_address($order_id,$db_index);

$address_arr=split_words($address, 35);

foreach($address_arr as $adr)
{

}*/

$sqla=mysql_query("SELECT b_address,b_city,b_county,b_state,b_country,b_zipcode FROM ".$db_index."orders WHERE orderid=".$order_id);
$result=mysql_fetch_assoc($sqla);

$iaddr1=$result['b_address'];
$string = trim($iaddr1);

$pos1 = strpos($string, "\n");
$pos2 = strpos($string, "\r");
if($pos1 !== false)
{
$string1=substr($string, 0, $pos1);
$string2=substr($string, $pos1,strlen($string));

$string1 = str_replace("\n", "", $string1);
$string2 = str_replace("\n", "", $string2);
}
elseif($pos2 !== false)
{
$string1=substr($string, 0, $pos2);
$string2=substr($string, $pos2,strlen($string));

$string1 = str_replace("\r", "", $string1);
$string2 = str_replace("\r", "", $string2);
}
else
{
$string1=$string;
$string2='';
}

$iaddr1=$string1;
$iaddr2=$string2;
$iaddr3=substr($result['b_city'], 0, 35);
$iaddr4=substr($result['b_county'], 0, 35);

$ipcode=substr($result['b_zipcode'], 0, 8);
$icountry=$result['b_country'];


$sqlg=mysql_query("SELECT s_title FROM ".$db_index."orders WHERE orderid=".$order_id);
$result=mysql_fetch_assoc($sqlg);
$dtitlename=$result['s_title'];

$dinitials='';

$sqlh=mysql_query("SELECT s_lastname,s_firstname FROM ".$db_index."orders WHERE orderid=".$order_id);
$result=mysql_fetch_assoc($sqlh);
$dname=$result['s_firstname'].' '.$result['s_lastname'];
 

$sqlk=mysql_query("SELECT s_address,s_city,s_county,s_state,s_country,s_zipcode,total,shipping_cost,shipping FROM ".$db_index."orders WHERE orderid=".$order_id);
$result=mysql_fetch_assoc($sqlk);
//print_r($result);


$daddr1=$result['s_address'];
$string = trim($daddr1);

$pos1 = strpos($string, "\n");
$pos2 = strpos($string, "\r");
if($pos1 !== false)
{
$string1=substr($string, 0, $pos1);
$string2=substr($string, $pos1,strlen($string));

$string1 = str_replace("\n", "", $string1);
$string2 = str_replace("\n", "", $string2);
}
elseif($pos2 !== false)
{
$string1=substr($string, 0, $pos2);
$string2=substr($string, $pos2,strlen($string));

$string1 = str_replace("\r", "", $string1);
$string2 = str_replace("\r", "", $string2);
}
else
{
$string1=$string;
$string2='';
}


$daddr1=$string1;
$daddr2=$string2;
$daddr3=substr($result['s_city'], 0, 35);
$daddr4=substr($result['s_county'], 0, 35);

$dpcode=substr($result['s_zipcode'], 0, 8);
$dcountry=$result['s_country'];


$price=$result['total']*100;
$delivery=$result['shipping_cost']*100;




$service=""; // ????????????????????????????????? Parcel Force Next Day (excludes weekends),PLEASE NOTE: for physical publication deliveries outside the UK please call our office on +44 (0)020 8204 5060 to make arrangement as we currently do not process these, however you may purchase ePapers from outside the UK.,
//Royal Mail 1st Class,Royal Mail 1st Class Recorded Delivery 

if(trim($result['shipping']) == 'Standard (2 - 5 Days)')
$service="001";
elseif( trim($result['shipping']) == 'Premium (1 - 2 Days)')
$service="002";
elseif( trim($result['shipping']) == 'Courier (2 - 5 Days)')
$service="005";
elseif( trim($result['shipping']) == 'Courier (1 - 2 Days)')
$service="006";

elseif( trim($result['shipping']) == 'Royal Mail / Courier (2 - 5 Days)')
$service="005";


$giftwrap="N";

if(trim($result['shipping']) == 'Standard (2 - 5 Days)')
$signature="Y";
elseif( trim($result['shipping']) == 'Premium (1 - 2 Days)')
$signature="Y";
elseif( trim($result['shipping']) == 'Courier (2 - 5 Days)')
$signature="N";
elseif( trim($result['shipping']) == 'Courier (1 - 2 Days)')
$signature="N";
elseif( trim($result['shipping']) == 'Royal Mail / Courier (2 - 5 Days)')
$signature="N";

$batchref=$order_id;
$maxwait=30;
$comm1='';
$comm2='';
$comm3='';
$comm4='';



// detail

$product_ids=get_non_downloadable($order_id,$db_index);



$index=0;

foreach ($product_ids as $prod)
{

$prod_det_array[$index]['prod_id']=$prod;
/* this code is not needed. it creates duplicates in gardners table
$last_unique_reference++;
mysql_query("INSERT INTO ".$db_index."gardners (unique_reference,order_id) VALUES ('".number_pad($last_unique_reference,9)."',".$order_id.")") or die(mysql_error());

$prod_det_array[$index]['unique_reference']=$last_unique_reference;*/
//$prod_det_array[$index]['additional_reference']=str_replace('-','',$order_details['refrence_number']);
$prod_det_array[$index]['order_id']=$order_details['orderid'];

$sqla=mysql_query("SELECT value FROM ".$db_index."extra_field_values WHERE fieldid=10 AND productid=".$prod);
$result=mysql_fetch_assoc($sqla);

$prod_det_array[$index]['isbn']=$result['value'];

$sqlb=mysql_query("SELECT amount FROM ".$db_index."order_details WHERE orderid=".$order_id." AND productid=".$prod);
$result=mysql_fetch_assoc($sqlb);
$prod_det_array[$index]['quantity']=$result['amount'];

$prod_det_array[$index]['gardnersref']='0';
$gardnersref='0';
if($index != 0)
{

$prod_det_array[$index]['price']=0;
$prod_det_array[$index]['delivery']=0;
}
else
{
$prod_det_array[$index]['price']=$price;
$prod_det_array[$index]['delivery']=$delivery;
}

$index++;

}


$index=0;
$bundle_products=array();

foreach ($prod_det_array as $prod)
{

$bundle_prod=get_bundled($order_id,$prod['prod_id'],$db_index,$index);
//print_r($bundle_prod);
if(!empty($bundle_prod))
{

foreach($bundle_prod as $bundle)
{
$bundle_products[]=$bundle;
}

unset($prod_det_array[$index]);
}

$index++;
}

// add the singele, non bundled products at the end of the bundled product array
//print_r($bundle_products);

$sum_array=$bundle_products;
foreach ($prod_det_array as $prod)
{
$sum_array[]=$prod;
}


$final_product_array=remove_duplicate_prod($sum_array);

/*
$test_arr[]=array ( 'prod_id' => 147 ,'unique_reference' => 462 ,'additional_reference' => 'M2109111821581' ,'isbn' => 9781871993318 ,'quantity' => 1, 'gardnersref' => 0 ,'price' => 9000 ,'delivery' => 0 );
 $test_arr[]=array ( 'prod_id' => 399 ,'unique_reference' => 463, 'additional_reference' => 'M2109111821581', 'isbn' => 9780708719879 ,'quantity' => 1, 'gardnersref' => 0, 'price' => 0, 'delivery' => 0 ) ;
 $test_arr[]=array ( 'prod_id' => 147 ,'unique_reference' => 462, 'additional_reference' => 'M2109111821581' ,'isbn' => 9781871993318, 'quantity' => 3 ,'gardnersref' => 0 ,'price' => 9000 ,'delivery' => 0 );
 $test_arr[]=array ('prod_id' => 671 ,'unique_reference' => 464, 'additional_reference' => 'M2109111821581' ,'isbn' => 9780708720493 ,'quantity' => 1, 'gardnersref' => 0 ,'price' => 0 ,'delivery' => 0 );
$test_arr[]=array ( 'prod_id' => 402 ,'unique_reference' => 465 ,'additional_reference' => 'M2109111821581' ,'isbn' => 9780708719862 ,'quantity' => 1 ,'gardnersref' => 0 ,'price' => 0 ,'delivery' => 0 ) ;
$test_arr[]=array ( 'prod_id' => 669 ,'unique_reference' => 466 ,'additional_reference' => 'M2109111821581' ,'isbn' => 9780708720479, 'quantity' => 1, 'gardnersref' => 0 ,'price' => 0 ,'delivery' => 0 ) ;
$test_arr[]=array ( 'prod_id' => 147 ,'unique_reference' => 462, 'additional_reference' => 'M2109111821581' ,'isbn' => 9781871993318, 'quantity' => 3 ,'gardnersref' => 0 ,'price' => 9000 ,'delivery' => 0 ); 
$test_arr[]=array ( 'prod_id' => 402 ,'unique_reference' => 465 ,'additional_reference' => 'M2109111821581' ,'isbn' => 9780708719862 ,'quantity' => 1 ,'gardnersref' => 0 ,'price' => 0 ,'delivery' => 0 ) ;
$final_product_array=remove_duplicate_prod($test_arr);
echo 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
print_r($final_product_array);
echo 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';*/
date_default_timezone_set('GMT');
$order_timestamp=date('Y:m:d H:i:s');
foreach($final_product_array as $item)
{
$last_unique_reference++;

mysql_query("INSERT INTO ".$db_index."gardners (unique_reference,prod_id,time,order_id) VALUES ('".number_pad($last_unique_reference,9)."','".$item['prod_id']."','".$order_timestamp."',".$order_id.")") or die(mysql_error());

$details[]= '"DETAIL",'.number_pad($last_unique_reference,9).',"'.$item['order_id'].'","'.$item['isbn'].'",'.$item['quantity'].','.$item['gardnersref']."\r\n".
'"TITLENAME'.'","'.$titlename."\" \r\n".
'"INITIALS'.'","'.$initials."\" \r\n".
'"INAME'.'","'.$iname."\" \r\n".
'"IADDR1'.'","'.$iaddr1."\" \r\n".
'"IADDR2'.'","'.$iaddr2."\" \r\n".
'"IADDR3'.'","'.$iaddr3."\" \r\n".
'"IADDR4'.'","'.$iaddr4."\" \r\n".
'"IPCODE'.'","'.$ipcode."\" \r\n".
'"ICOUNTRY'.'","'.$icountry."\" \r\n".
'"DTITLENAME'.'","'.$dtitlename."\" \r\n".
'"DINITIALS'.'","'.$dinitials."\" \r\n".
'"DNAME'.'","'.$dname."\" \r\n".
'"DADDR1'.'","'.$daddr1."\" \r\n".
'"DADDR2'.'","'.$daddr2."\" \r\n".
'"DADDR3'.'","'.$daddr3."\" \r\n".
'"DADDR4'.'","'.$daddr4."\" \r\n".
'"DPCODE'.'","'.$dpcode."\" \r\n".
'"DCOUNTRY'.'","'.$dcountry."\" \r\n".
'"PRICE'.'",0'." \r\n".
'"DELIVERY'.'",0'." \r\n".
'"SERVICE'.'",'.$service." \r\n".
'"GIFTWRAP'.'","'.$giftwrap."\" \r\n".
'"SIGNATURE'.'","'.$signature."\" \r\n".
'"BATCHREF'.'","'.$batchref."\" \r\n".
'"MAXWAIT'.'",'.$maxwait." \r\n".
'"COMM1'.'","'.$comm1."\" \r\n".
'"COMM2'.'","'.$comm2."\" \r\n".
'"COMM3'.'","'.$comm3."\" \r\n".
'"COMM4'.'","'.$comm4."\" \r\n";

}

//print_r($details);
// Trailer Record
$trailer='"TRAILER'.'",'.number_pad(sizeof($final_product_array),6)."\r\n";

$det=implode("",$details);


$order=$header.$det.$trailer;
return $order;


}




function write_to_file($order_id,$db_index,$host,$user,$pass,$dir)
{
$prod=get_non_downloadable($order_id,$db_index);
//print_r($prod);

$sqla=mysql_query("SELECT g_status FROM ".$db_index."orders WHERE orderid=".$order_id);
$result=mysql_fetch_assoc($sqla);
$status=$result['g_status'];




if((!empty($prod)) && ($status == ''))

{
$myFile = $order_id.".ORD";
$fh = fopen($myFile, 'wb');
$stringData = create_order_file($order_id,$db_index);
fwrite($fh, $stringData);
fclose($fh);

order_send($order_id,$host,$user,$pass,$dir,$db_index);


}
else
{
if(empty($prod))
return 'Failed! Order has only downloadable products!';
elseif($status != '')
return 'Failed! Order allready sent!';

}

}


//$order_id=$_GET['order'];
$order_id=$_POST['order'];
echo (write_to_file($order_id,$db_index,$host,$user,$pass,$destDir));

//create_order_file(393,$db_index);
//$array=get_non_downloadable(982,$db_index);

//print_r($array);



?>
