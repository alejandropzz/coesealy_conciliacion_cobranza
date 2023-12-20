USE coedb_2019;

DROP TABLE imports;

CREATE TABLE imports(
    emition_month       INT NOT NULL,
    year                INT NOT NULL,
    vendor_code         VARCHAR(8),
    vendor_rfc          VARCHAR(20),
    vendor_name         VARCHAR(150) NOT NULL,
    customs_pediment    VARCHAR(20) NOT NULL,
    invoice_number      VARCHAR(20) NOT NULL,
    invoice_date        DATE        NOT NULL,
    usd_brute_import    DECIMAL(11,2),
    iva_usd             DECIMAL(11,2),
    other_usd_taxes     DECIMAL(11,2),
    retemption_usd      DECIMAL(11,2),
    net_import_usd      DECIMAL(11,2),
    total_vendor_usd    DECIMAL(11,2),
    exchange_rate_reg   DECIMAL(11,2) NOT NULL,
    exchange_rate_close DECIMAL(11,2) NOT NULL,
    mxp_brute_import    DECIMAL(11,2) NOT NULL,
    iva_mxp             DECIMAL(11,2) NOT NULL,
    other_mxp_taxes     DECIMAL(11,2),
    retemption_mxp      DECIMAL(11,2),
    net_import_mxp      DECIMAL(11,2) NOT NULL,
    total_vendor_mxp    DECIMAL(11,2),
    total_report_amt    DECIMAL(11,2),
    gl_type             CHAR(2) NOT NULL,
    gl_number           VARCHAR(25) NOT NULL,
    peca                VARCHAR(25) NOT NULL,
    uuid                VARCHAR(65),

    CONSTRAINT `multi_import` PRIMARY KEY(vendor_code,invoice_number,mxp_brute_import)
);