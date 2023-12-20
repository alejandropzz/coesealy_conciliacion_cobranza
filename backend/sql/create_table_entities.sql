USE coedb_2019;

DROP TABLE entities;

CREATE TABLE entities(
    company VARCHAR(15) NOT NULL,
    entity  VARCHAR(4) NOT NULL,

    CONSTRAINT `entities_actual_pk` PRIMARY KEY(entity),
    INDEX `entities_actual_consulting_index` (company)
);