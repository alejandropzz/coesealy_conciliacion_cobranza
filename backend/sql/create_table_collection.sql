USE coedb_2019;

DROP TABLE fund_collection;

CREATE TABLE fund_collection(
    id INT AUTO_INCREMENT,
    client_number       INT NOT NULL,
    client_name         VARCHAR(100) NOT NULL,
    invoice_date        DATE,
    invoice_number      INT,
    invoice_import      DECIMAL(11,2) NOT NULL, 
    payment_id          VARCHAR(10) NOT NULL,
    usd_invoice_import  DECIMAL(11,2),
    exchange_rate       DECIMAL(11,2),
    mxn_invoice_import  DECIMAL(11,2) NOT NULL,
    bank_payment_date   DATE NOT NULL,
    bank_name           VARCHAR(50),
    bank_account        INT NOT NULL,
    total               DECIMAL(11,2) NOT NULL,
    applying_month      INT,
    gl_accounting_date  DATE,
    gl_journal_type     VARCHAR(2) NOT NULL,
    gl_number           VARCHAR(25) NOT NULL,
    uuid                VARCHAR(45) NOT NULL,
    linked              INT NOT NULL,

    CONSTRAINT `multi_fund_collect` PRIMARY KEY(client_number,payment_id,mxn_invoice_import),
        INDEX `index_id` (id)

);