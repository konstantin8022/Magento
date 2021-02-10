# Magento_SMTP old 
<sales_email_order_items>

        <block type="sales/order_email_items" name="items" template="email/order/items.phtml">

            <action method="addItemRender"><type>default</type><block>sales/order_email_items_order_default</block><template>email/order/item

s/order/default.phtml</template></action>

            <action method="addItemRender"><type>grouped</type><block>sales/order_email_items_order_grouped</block><template>email/order/item

s/order/default.phtml</template></action>

            <block type="core/template" name="order_totals_wrapper" as="order_totals" template="email/order/totals/wrapper.phtml">

                <block type="sales/order_totals" name="order_totals" template="sales/order/totals.phtml">

                    <action method="setLabelProperties"><value>colspan="3" align="right" style="padding:3px 9px"</value></action>

                    <action method="setValueProperties"><value>align="right" style="padding:3px 9px"</value></action>

                    <block type="tax/sales_order_tax" name="tax" template="tax/order/tax.phtml">

                        <action method="setIsPlaneMode"><value>1</value></action>

                    </block>

                </block>

            </block>

        </block>

        <block type="core/text_list" name="additional.product.info" />

    </sales_email_order_items>

p385567:/home/www/p385567/html/app > grep -iR sales_email_order_items ./* | grep xml

./design/frontend/rwd/default/layout/bundle.xml:    <sales_email_order_items>

./design/frontend/rwd/default/layout/bundle.xml:    </sales_email_order_items>

./design/frontend/rwd/default/layout/sales.xml:    <sales_email_order_items>

./design/frontend/rwd/default/layout/sales.xml:    </sales_email_order_items>

./design/frontend/rwd/default/layout/downloadable.xml:    <sales_email_order_items>

./design/frontend/rwd/default/layout/downloadable.xml:    </sales_email_order_items>

./design/frontend/base/default/layout/bundle.xml:    <sales_email_order_items>

./design/frontend/base/default/layout/bundle.xml:    </sales_email_order_items>

./design/frontend/base/default/layout/sales.xml:    <sales_email_order_items>

./design/frontend/base/default/layout/sales.xml:    </sales_email_order_items>

./design/frontend/base/default/layout/downloadable.xml:    <sales_email_order_items>

./design/frontend/base/default/layout/downloadable.xml:    </sales_email_order_items>

./design/frontend/default/iphone/layout/bundle.xml:    <sales_email_order_items>

./design/frontend/default/iphone/layout/bundle.xml:    </sales_email_order_items>

./design/frontend/default/iphone/layout/sales.xml:    <sales_email_order_items>

./design/frontend/default/iphone/layout/sales.xml:    </sales_email_order_items>

./design/frontend/default/modern/layout/sales.xml:    <sales_email_order_items>

./design/frontend/default/modern/layout/sales.xml:    </sales_email_order_items>

p385567:/home/www/p385567/html/app > 






mysql> show tables like '%dhl%';
+---------------------------------+
| Tables_in_usr_p385567_4 (%dhl%) |
+---------------------------------+
| dhl_versenden_label_status      |
+---------------------------------+
1 row in set (0,00 sec)

mysql> show tables like '%sales_flat_quote_address%';
+------------------------------------------------------+
| Tables_in_usr_p385567_4 (%sales_flat_quote_address%) |
+------------------------------------------------------+
| sales_flat_quote_address                             |
| sales_flat_quote_address_item                        |
+------------------------------------------------------+
2 rows in set (0,00 sec)

mysql> show tables like '%sales_flat_order_address%';
+------------------------------------------------------+
| Tables_in_usr_p385567_4 (%sales_flat_order_address%) |
+------------------------------------------------------+
| sales_flat_order_address                             |
+------------------------------------------------------+
1 row in set (0,00 sec)

mysql> show tables like '%core_resource%';
+-------------------------------------------+
| Tables_in_usr_p385567_4 (%core_resource%) |
+-------------------------------------------+
| core_resource                             |
+-------------------------------------------+
1 row in set (0,00 sec)

mysql> show tables like '%setup%';
Empty set (0,00 sec)

mysql> describe  dhl_versenden_label_status;
+-------------+----------------------+------+-----+---------+-------+
| Field       | Type                 | Null | Key | Default | Extra |
+-------------+----------------------+------+-----+---------+-------+
| order_id    | int(10) unsigned     | NO   | PRI | NULL    |       |
| status_code | smallint(5) unsigned | NO   | MUL | 0       |       |
+-------------+----------------------+------+-----+---------+-------+
2 rows in set (0,00 sec)

mysql> describe   sales_flat_quote_address ;
+-------------------------------+----------------------+------+-----+---------------------+----------------+
| Field                         | Type                 | Null | Key | Default             | Extra          |
+-------------------------------+----------------------+------+-----+---------------------+----------------+
| address_id                    | int(10) unsigned     | NO   | PRI | NULL                | auto_increment |
| quote_id                      | int(10) unsigned     | NO   | MUL | 0                   |                |
| created_at                    | timestamp            | NO   |     | 0000-00-00 00:00:00 |                |
| updated_at                    | timestamp            | NO   |     | 0000-00-00 00:00:00 |                |
| customer_id                   | int(10) unsigned     | YES  |     | NULL                |                |
| save_in_address_book          | smallint(6)          | YES  |     | 0                   |                |
| customer_address_id           | int(10) unsigned     | YES  |     | NULL                |                |
| address_type                  | varchar(255)         | YES  |     | NULL                |                |
| email                         | varchar(255)         | YES  |     | NULL                |                |
| prefix                        | varchar(40)          | YES  |     | NULL                |                |
| firstname                     | varchar(255)         | YES  |     | NULL                |                |
| middlename                    | varchar(40)          | YES  |     | NULL                |                |
| lastname                      | varchar(255)         | YES  |     | NULL                |                |
| suffix                        | varchar(40)          | YES  |     | NULL                |                |
| company                       | varchar(255)         | YES  |     | NULL                |                |
| street                        | varchar(255)         | YES  |     | NULL                |                |
| city                          | varchar(255)         | YES  |     | NULL                |                |
| region                        | varchar(255)         | YES  |     | NULL                |                |
| region_id                     | int(10) unsigned     | YES  |     | NULL                |                |
| postcode                      | varchar(255)         | YES  |     | NULL                |                |
| country_id                    | varchar(255)         | YES  |     | NULL                |                |
| telephone                     | varchar(255)         | YES  |     | NULL                |                |
| fax                           | varchar(255)         | YES  |     | NULL                |                |
| same_as_billing               | smallint(5) unsigned | NO   |     | 0                   |                |
| free_shipping                 | smallint(5) unsigned | NO   |     | 0                   |                |
| collect_shipping_rates        | smallint(5) unsigned | NO   |     | 0                   |                |
| shipping_method               | varchar(255)         | YES  |     | NULL                |                |
| shipping_description          | varchar(255)         | YES  |     | NULL                |                |
| weight                        | decimal(12,4)        | NO   |     | 0.0000              |                |
| subtotal                      | decimal(12,4)        | NO   |     | 0.0000              |                |
| base_subtotal                 | decimal(12,4)        | NO   |     | 0.0000              |                |
| subtotal_with_discount        | decimal(12,4)        | NO   |     | 0.0000              |                |
| base_subtotal_with_discount   | decimal(12,4)        | NO   |     | 0.0000              |                |
| tax_amount                    | decimal(12,4)        | NO   |     | 0.0000              |                |
| base_tax_amount               | decimal(12,4)        | NO   |     | 0.0000              |                |
| shipping_amount               | decimal(12,4)        | NO   |     | 0.0000              |                |
| base_shipping_amount          | decimal(12,4)        | NO   |     | 0.0000              |                |
| shipping_tax_amount           | decimal(12,4)        | YES  |     | NULL                |                |
| base_shipping_tax_amount      | decimal(12,4)        | YES  |     | NULL                |                |
| discount_amount               | decimal(12,4)        | NO   |     | 0.0000              |                |
| base_discount_amount          | decimal(12,4)        | NO   |     | 0.0000              |                |
| grand_total                   | decimal(12,4)        | NO   |     | 0.0000              |                |
| base_grand_total              | decimal(12,4)        | NO   |     | 0.0000              |                |
| customer_notes                | text                 | YES  |     | NULL                |                |
| applied_taxes                 | text                 | YES  |     | NULL                |                |
| discount_description          | varchar(255)         | YES  |     | NULL                |                |
| shipping_discount_amount      | decimal(12,4)        | YES  |     | NULL                |                |
| base_shipping_discount_amount | decimal(12,4)        | YES  |     | NULL                |                |
| subtotal_incl_tax             | decimal(12,4)        | YES  |     | NULL                |                |
| base_subtotal_total_incl_tax  | decimal(12,4)        | YES  |     | NULL                |                |
| hidden_tax_amount             | decimal(12,4)        | YES  |     | NULL                |                |
| base_hidden_tax_amount        | decimal(12,4)        | YES  |     | NULL                |                |
| shipping_hidden_tax_amount    | decimal(12,4)        | YES  |     | NULL                |                |
| base_shipping_hidden_tax_amnt | decimal(12,4)        | YES  |     | NULL                |                |
| shipping_incl_tax             | decimal(12,4)        | YES  |     | NULL                |                |
| base_shipping_incl_tax        | decimal(12,4)        | YES  |     | NULL                |                |
| vat_id                        | text                 | YES  |     | NULL                |                |
| vat_is_valid                  | smallint(6)          | YES  |     | NULL                |                |
| vat_request_id                | text                 | YES  |     | NULL                |                |
| vat_request_date              | text                 | YES  |     | NULL                |                |
| vat_request_success           | smallint(6)          | YES  |     | NULL                |                |
| gift_message_id               | int(11)              | YES  |     | NULL                |                |
| payone_addresscheck_score     | varchar(1)           | NO   |     |                     |                |
| payone_addresscheck_date      | datetime             | NO   |     | 0000-00-00 00:00:00 |                |
| payone_addresscheck_hash      | varchar(32)          | NO   |     |                     |                |
| payone_protect_score          | varchar(1)           | NO   |     |                     |                |
| payone_protect_date           | datetime             | NO   |     | 0000-00-00 00:00:00 |                |
| payone_protect_hash           | varchar(32)          | NO   |     |                     |                |
| dhl_versenden_info            | text                 | YES  |     | NULL                |                |
+-------------------------------+----------------------+------+-----+---------------------+----------------+
69 rows in set (0,00 sec)

mysql> describe   sales_flat_quote_address_item  ;
+-------------------------+------------------+------+-----+---------------------+----------------+
| Field                   | Type             | Null | Key | Default             | Extra          |
+-------------------------+------------------+------+-----+---------------------+----------------+
| address_item_id         | int(10) unsigned | NO   | PRI | NULL                | auto_increment |
| parent_item_id          | int(10) unsigned | YES  | MUL | NULL                |                |
| quote_address_id        | int(10) unsigned | NO   | MUL | 0                   |                |
| quote_item_id           | int(10) unsigned | NO   | MUL | 0                   |                |
| created_at              | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
| updated_at              | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
| applied_rule_ids        | text             | YES  |     | NULL                |                |
| additional_data         | text             | YES  |     | NULL                |                |
| weight                  | decimal(12,4)    | YES  |     | 0.0000              |                |
| qty                     | decimal(12,4)    | NO   |     | 0.0000              |                |
| discount_amount         | decimal(12,4)    | YES  |     | 0.0000              |                |
| tax_amount              | decimal(12,4)    | YES  |     | 0.0000              |                |
| row_total               | decimal(12,4)    | NO   |     | 0.0000              |                |
| base_row_total          | decimal(12,4)    | NO   |     | 0.0000              |                |
| row_total_with_discount | decimal(12,4)    | YES  |     | 0.0000              |                |
| base_discount_amount    | decimal(12,4)    | YES  |     | 0.0000              |                |
| base_tax_amount         | decimal(12,4)    | YES  |     | 0.0000              |                |
| row_weight              | decimal(12,4)    | YES  |     | 0.0000              |                |
| product_id              | int(10) unsigned | YES  |     | NULL                |                |
| super_product_id        | int(10) unsigned | YES  |     | NULL                |                |
| parent_product_id       | int(10) unsigned | YES  |     | NULL                |                |
| sku                     | varchar(255)     | YES  |     | NULL                |                |
| image                   | varchar(255)     | YES  |     | NULL                |                |
| name                    | varchar(255)     | YES  |     | NULL                |                |
| description             | text             | YES  |     | NULL                |                |
| free_shipping           | int(10) unsigned | YES  |     | NULL                |                |
| is_qty_decimal          | int(10) unsigned | YES  |     | NULL                |                |
| price                   | decimal(12,4)    | YES  |     | NULL                |                |
| discount_percent        | decimal(12,4)    | YES  |     | NULL                |                |
| no_discount             | int(10) unsigned | YES  |     | NULL                |                |
| tax_percent             | decimal(12,4)    | YES  |     | NULL                |                |
| base_price              | decimal(12,4)    | YES  |     | NULL                |                |
| base_cost               | decimal(12,4)    | YES  |     | NULL                |                |
| price_incl_tax          | decimal(12,4)    | YES  |     | NULL                |                |
| base_price_incl_tax     | decimal(12,4)    | YES  |     | NULL                |                |
| row_total_incl_tax      | decimal(12,4)    | YES  |     | NULL                |                |
| base_row_total_incl_tax | decimal(12,4)    | YES  |     | NULL                |                |
| hidden_tax_amount       | decimal(12,4)    | YES  |     | NULL                |                |
| base_hidden_tax_amount  | decimal(12,4)    | YES  |     | NULL                |                |
| gift_message_id         | int(11)          | YES  |     | NULL                |                |
+-------------------------+------------------+------+-----+---------------------+----------------+
40 rows in set (0,00 sec)

mysql> describe   sales_flat_order_address   ;
+---------------------+------------------+------+-----+---------+----------------+
| Field               | Type             | Null | Key | Default | Extra          |
+---------------------+------------------+------+-----+---------+----------------+
| entity_id           | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
| parent_id           | int(10) unsigned | YES  | MUL | NULL    |                |
| customer_address_id | int(11)          | YES  |     | NULL    |                |
| quote_address_id    | int(11)          | YES  |     | NULL    |                |
| region_id           | int(11)          | YES  |     | NULL    |                |
| customer_id         | int(11)          | YES  |     | NULL    |                |
| fax                 | varchar(255)     | YES  |     | NULL    |                |
| region              | varchar(255)     | YES  |     | NULL    |                |
| postcode            | varchar(255)     | YES  |     | NULL    |                |
| lastname            | varchar(255)     | YES  |     | NULL    |                |
| street              | varchar(255)     | YES  |     | NULL    |                |
| city                | varchar(255)     | YES  |     | NULL    |                |
| email               | varchar(255)     | YES  |     | NULL    |                |
| telephone           | varchar(255)     | YES  |     | NULL    |                |
| country_id          | varchar(2)       | YES  |     | NULL    |                |
| firstname           | varchar(255)     | YES  |     | NULL    |                |
| address_type        | varchar(255)     | YES  |     | NULL    |                |
| prefix              | varchar(255)     | YES  |     | NULL    |                |
| middlename          | varchar(255)     | YES  |     | NULL    |                |
| suffix              | varchar(255)     | YES  |     | NULL    |                |
| company             | varchar(255)     | YES  |     | NULL    |                |
| vat_id              | text             | YES  |     | NULL    |                |
| vat_is_valid        | smallint(6)      | YES  |     | NULL    |                |
| vat_request_id      | text             | YES  |     | NULL    |                |
| vat_request_date    | text             | YES  |     | NULL    |                |
| vat_request_success | smallint(6)      | YES  |     | NULL    |                |
| dhl_versenden_info  | text             | YES  |     | NULL    |                |
+---------------------+------------------+------+-----+---------+----------------+
27 rows in set (0,00 sec)

mysql> describe   core_resource  ;
+--------------+-------------+------+-----+---------+-------+
| Field        | Type        | Null | Key | Default | Extra |
+--------------+-------------+------+-----+---------+-------+
| code         | varchar(50) | NO   | PRI | NULL    |       |
| version      | varchar(50) | YES  |     | NULL    |       |
| data_version | varchar(50) | YES  |     | NULL    |       |
+--------------+-------------+------+-----+---------+-------+
3 rows in set (0,00 sec)

mysql> show tables like '%intraship_shipment%';
Empty set (0,00 sec)

mysql> show tables like '%intraship%';
Empty set (0,00 sec)

mysql> show tables like '%intraship_document%';
Empty set (0,00 sec)

mysql> show tables like '%core_config_data%';
+----------------------------------------------+
| Tables_in_usr_p385567_4 (%core_config_data%) |
+----------------------------------------------+
| core_config_data                             |
| core_config_data_compare                     |
+----------------------------------------------+
2 rows in set (0,00 sec)

mysql> describe  core_config_data ;
+-----------+------------------+------+-----+---------+----------------+
| Field     | Type             | Null | Key | Default | Extra          |
+-----------+------------------+------+-----+---------+----------------+
| config_id | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
| scope     | varchar(8)       | NO   | MUL | default |                |
| scope_id  | int(11)          | NO   |     | 0       |                |
| path      | varchar(255)     | NO   |     | general |                |
| value     | text             | YES  |     | NULL    |                |
+-----------+------------------+------+-----+---------+----------------+
5 rows in set (0,00 sec)




mysql> describe  sales_order_status;
+--------+--------------+------+-----+---------+-------+
| Field  | Type         | Null | Key | Default | Extra |
+--------+--------------+------+-----+---------+-------+
| status | varchar(32)  | NO   | PRI | NULL    |       |
| label  | varchar(128) | NO   |     | NULL    |       |
+--------+--------------+------+-----+---------+-------+
2 rows in set (0,00 sec)

mysql> describe  sales_order_status_label;
+----------+----------------------+------+-----+---------+-------+
| Field    | Type                 | Null | Key | Default | Extra |
+----------+----------------------+------+-----+---------+-------+
| status   | varchar(32)          | NO   | PRI | NULL    |       |
| store_id | smallint(5) unsigned | NO   | PRI | NULL    |       |
| label    | varchar(128)         | NO   |     | NULL    |       |
+----------+----------------------+------+-----+---------+-------+
3 rows in set (0,01 sec)

mysql> describe  sales_order_status_state;
+------------+----------------------+------+-----+---------+-------+
| Field      | Type                 | Null | Key | Default | Extra |
+------------+----------------------+------+-----+---------+-------+
| status     | varchar(32)          | NO   | PRI | NULL    |       |
| state      | varchar(32)          | NO   | PRI | NULL    |       |
| is_default | smallint(5) unsigned | NO   |     | 0       |       |
+------------+----------------------+------+-----+---------+-------+
3 rows in set (0,00 sec)

mysql> describe  sales_order_tax;
+------------------+----------------------+------+-----+---------+----------------+
| Field            | Type                 | Null | Key | Default | Extra          |
+------------------+----------------------+------+-----+---------+----------------+
| tax_id           | int(10) unsigned     | NO   | PRI | NULL    | auto_increment |
| order_id         | int(10) unsigned     | NO   | MUL | NULL    |                |
| code             | varchar(255)         | YES  |     | NULL    |                |
| title            | varchar(255)         | YES  |     | NULL    |                |
| percent          | decimal(12,4)        | YES  |     | NULL    |                |
| amount           | decimal(12,4)        | YES  |     | NULL    |                |
| priority         | int(11)              | NO   |     | NULL    |                |
| position         | int(11)              | NO   |     | NULL    |                |
| base_amount      | decimal(12,4)        | YES  |     | NULL    |                |
| process          | smallint(6)          | NO   |     | NULL    |                |
| base_real_amount | decimal(12,4)        | YES  |     | NULL    |                |
| hidden           | smallint(5) unsigned | NO   |     | 0       |                |
+------------------+----------------------+------+-----+---------+----------------+
12 rows in set (0,00 sec)

mysql> describe  sales_order_tax_item;
+-------------+------------------+------+-----+---------+----------------+
| Field       | Type             | Null | Key | Default | Extra          |
+-------------+------------------+------+-----+---------+----------------+
| tax_item_id | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
| tax_id      | int(10) unsigned | NO   | MUL | NULL    |                |
| item_id     | int(10) unsigned | NO   | MUL | NULL    |                |
| tax_percent | decimal(12,4)    | NO   |     | NULL    |                |
+-------------+------------------+------+-----+---------+----------------+
4 rows in set (0,00 sec)

mysql>
p385567:/home/www/p385567/html > cd app/code/community/Dhl/Intraship
-bash: cd: app/code/community/Dhl/Intraship: Datei oder Verzeichnis nicht gefunden
p385567:/home/www/p385567/html > cd app/code/community/Dhl/
p385567:/home/www/p385567/html/app/code/community/Dhl > ls
Versenden
p385567:/home/www/p385567/html/app/code/community/Dhl > ls Versenden/
Block  controllers  data  etc  Helper  Model  sql
p385567:/home/www/p385567/html/app/code/community/Dhl > cd ../../../
p385567:/home/www/p385567/html/app > cd .
p385567:/home/www/p385567/html/app > cd ..
p385567:/home/www/p385567/html > ls dhl-Dhl_Versenden-1.3.1/
app/         doc/         .DS_Store    lib/         package.xml  skin/
p385567:/home/www/p385567/html > ls dhl-Dhl_Versenden-1.3.1/app/
code  design  etc  locale
p385567:/home/www/p385567/html > ls dhl-Dhl_Versenden-1.3.1/app/code/
community
p385567:/home/www/p385567/html > ls dhl-Dhl_Versenden-1.3.1/app/code/community/
Dhl
p385567:/home/www/p385567/html > ls dhl-Dhl_Versenden-1.3.1/app/code/community/Dhl/
Versenden
p385567:/home/www/p385567/html >
