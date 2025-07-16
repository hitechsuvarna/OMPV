CREATE TABLE i_users (
iu_id int primary key auto_increment,
iu_username varchar(100),
iu_password varchar(100),
iu_type varchar(100),
iu_ref int,
iu_owner int,
iu_status varchar(10),
iu_subscription_start date,
iu_subscription_end date,
iu_created datetime,
iu_created_by int,
iu_modified datetime,
iu_modified_by int);

CREATE TABLE i_contacts (
ic_id int primary key auto_increment,
ic_name varchar(100),
ic_company varchar(100),
ic_phone varchar(100),
ic_phone_2 varchar(100),
ic_email varchar(100),
ic_email_2 varchar(100),
ic_website varchar(100),
ic_address varchar(500),
ic_gst_number varchar(100),
ic_section varchar(100),
ic_type varchar(100),
ic_owner int,
ic_created datetime,
ic_created_by int,
ic_modified datetime,
ic_modified_by int);

ALTER TABLE i_contacts ADD ic_bank_name VARCHAR(100), ADD ic_bank_branch VARCHAR(100), ADD ic_bank_accno VARCHAR(100), ADD ic_bank_ifsc VARCHAR(100);

CREATE TABLE i_products (
ip_id int primary key auto_increment,
ip_name varchar(100),
ip_description varchar(500),
ip_image varchar(100),
ip_hsn_code varchar(100),
ip_unit varchar(100),
ip_owner int,
ip_created datetime,
ip_created_by int,
ip_modified datetime,
ip_modified_by int);


CREATE TABLE i_p_pricing (
ipp_id int primary key auto_increment,
ipp_p_id int,
ipp_c_id int,
ipp_price int,
ipp_owner int);

CREATE TABLE i_inventory (
ii_id int primary key auto_increment,
ii_c_id int,
ii_order_id int,
ii_order_txn int,
ii_type varchar(100),
ii_p_id int,
ii_inward int,
ii_outward int,
ii_balance int,
ii_owner int,
ii_created datetime,
ii_created_by int,
ii_modified datetime,
ii_modified_by int);

CREATE TABLE i_txns (
it_id int primary key auto_increment,
it_type varchar(100),
it_c_id int,
it_date date,
it_txn_no varchar(100),
it_note varchar(500),
it_amount int,
it_status varchar(100),
it_owner int,
it_created datetime,
it_created_by int,
it_modified datetime,
it_modified_by int);

ALTER TABLE i_txns ADD it_discount int, ADD it_credit int, ADD it_freight int;

CREATE TABLE i_txns_details (
itd_id int primary key auto_increment,
itd_t_id int,
itd_p_id int,
itp_qty int,
itp_rate int,
itp_value int,
itp_tax_group_id int,
itp_tax int,
itp_amount int,
itp_owner int,
itp_created datetime,
itp_created_by int,
itp_modified datetime,
itp_modified_by int);

CREATE TABLE i_txns_history (
ith_id int primary key auto_increment,
ith_td_id int,
ith_req_qty int,
ith_app_qty int,
ith_rate int,
ith_owner int,
ith_approved datetime,
ith_approved_by int);

CREATE TABLE i_txn_payments (
itp_id int primary key auto_increment,
itp_t_id int,
itp_c_id int,
itp_date date,
itp_mode varchar(100),
itp_details varchar(100),
itp_amt int,
itp_owner int,
itp_created datetime,
itp_created_by int,
itp_modified datetime,
itp_modified_by int);

CREATE TABLE i_u_access (
iua_id int primary key auto_increment,
iua_u_id int,
iua_u_products varchar(10),
iua_u_pricing varchar(10),
iua_u_dealers varchar(10),
iua_u_vendors varchar(10),
iua_u_orders varchar(10),
iua_u_delivery varchar(10),
iua_u_inventory varchar(10),
iua_u_purchase varchar(10),
iua_u_expenses varchar(10),
iua_u_invoice varchar(10),
iua_u_credit_note varchar(10),
iua_u_debit_note varchar(10),
iua_u_payments varchar(10),
iua_u_tax varchar(10),
iua_u_users varchar(10),
iua_owner int,
iua_created datetime,
iua_created_by int,
iua_modified datetime,
iua_modified_by int);

CREATE TABLE i_u_cart (
iuc_id int primary key auto_increment,
iuc_u_id int,
iuc_u_owner int,
iuc_u_p_id int,
iuc_u_qty int,
iuc_u_note varchar(300),
iuc_owner int,
iuc_created datetime,
iuc_created_by int,
iuc_modified datetime,
iuc_modified_by int);

CREATE TABLE i_expenses (
ie_id int primary key auto_increment,
ie_description varchar(500),
ie_amount int,
ie_order_id int,
ie_status varchar(10),
ie_date date,
ie_owner int,
ie_created datetime,
ie_created_by int);

CREATE TABLE i_order_expenses (
ioe_id int primary key auto_increment,
ioe_e_id int,
ioe_start_time datetime,
ioe_end_time datetime,
ioe_owner int);

CREATE TABLE i_taxes (
itx_id int primary key auto_increment,
itx_name varchar(100),
itx_percent float,
itx_owner int);

CREATE TABLE i_tax_cess (
itxc_id int primary key auto_increment,
itxc_t_id int,
itxc_cess_name varchar(100));

CREATE TABLE i_p_taxes (
ipt_id int primary key auto_increment,
ipt_p_id int,
ipt_t_id int,
ipt_oid int,
ipt_created datetime,
ipt_created_by int,
ipt_modified datetime,
ipt_modified_by int);

CREATE TABLE i_tax_group (
ittxg_id int  primary key auto_increment,
ittxg_group_name varchar(100),
ittxg_owner int);

CREATE TABLE i_tax_group_collection (
itxgc_id int primary key auto_increment,
itxgc_tg_id int,
itxgc_tx_id int);

CREATE TABLE i_txn_transport_details (
ittd_id int primary key auto_increment,
ittd_txn_id int,
ittd_transporter varchar(200),
ittd_lrno varchar(200),
ittd_date date,
ittd_transporter_gstno varchar(100),
ittd_state varchar(100),
ittd_owner int);

CREATE TABLE i_txn_product_taxes (
itpt_id int primary key auto_increment,
itpt_txn_id int,
itpt_td_id int,
itpt_tx_id int,
itpt_p_id int,
itpt_t_name varchar(100),
itpt_t_amount int,
itpt_owner int);

