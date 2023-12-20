USE coedb_2019;

DROP TABLE IF EXISTS check_register;

CREATE TABLE check_register(
    id INT AUTO_INCREMENT,
    company_number VARCHAR(20),
    company VARCHAR(40),
    rfc VARCHAR(20),
    checker INT NOT NULL,
    payment_date DATE NOT NULL,
    emiting_month INT NOT NULL,
    bank VARCHAR(30) NOT NULL,
    vendor_name VARCHAR(50),
    vendor_number VARCHAR(8) NOT NULL,
    bill_number VARCHAR(15) NOT NULL,
    bill_date   DATE NOT NULL,
    origin_month INT NOT NULL,
    brute_import DECIMAL(11,2) NOT NULL,
    discount DECIMAL(11,2),
    bill_net_import DECIMAL(11,2) NOT NULL,
    vendor_net_import DECIMAL(11,2) NOT NULL,
    day_payment_import DECIMAL(11,2) NOT NULL,
    currency CHAR(3),
    gl_type CHAR(2),
    gl_number VARCHAR(25),
    lawson_date DATE,
    uuid_cfdi VARCHAR(45),
    uuid_date DATE,
	
    CONSTRAINT `multi_chk_uid` PRIMARY KEY(vendor_number,bill_number,bill_net_import),
    INDEX `index_id` (id)
);

