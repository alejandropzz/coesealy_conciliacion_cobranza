USE coedb_2019;

DROP TABLE IF EXISTS payable_manbase;

CREATE TABLE payable_manbase(
    id INT AUTO_INCREMENT,
    company_number VARCHAR(20),
    company VARCHAR(40),
    rfc VARCHAR(20),
    period_emit INT NOT NULL,
    vendor_number VARCHAR(8),
    vendor_name   VARCHAR(50),
    bill_number VARCHAR(10),
    bill_date   DATE,
    ingress_date DATE,
    brute_import DECIMAL(11,2),
    discount DECIMAL(11,2),
    net_import DECIMAL(11,2),
    currency CHAR(5),
    vencing_date DATE,
    manbase_account CHAR(2) NOT NULL, 
    manbase_account_comp CHAR(8) NOT NULL,
    dealer VARCHAR(50),
    account_description VARCHAR(50) NOT NULL,
    dist_amount DECIMAL(11,2) NOT NULL,
    type_movement CHAR(2) NOT NULL,
    misc_chg VARCHAR(20) NOT NULL,
    sls_div VARCHAR(20) NOT NULL, 
    reference VARCHAR(20) NOT NULL,
    gl_type CHAR(2),
    gl_number VARCHAR(25),
    lawson_date DATE,
    uuid_cfdi VARCHAR(45),
    uuid_date DATE,
	
    CONSTRAINT `multi_manbase_uuid` PRIMARY KEY(vendor_number,bill_number,dist_amount),
    INDEX `index_id` (id)
);

