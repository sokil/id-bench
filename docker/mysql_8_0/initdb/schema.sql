CREATE TABLE primary_autoincrement
(
    id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT
) Engine=InnoDB;

CREATE TABLE primary_uuid
(
    id BINARY(16) NOT NULL PRIMARY KEY
) Engine=InnoDB;

-- https://dev.mysql.com/doc/refman/5.7/en/innodb-index-types.html
-- https://dev.mysql.com/doc/refman/8.0/en/innodb-index-types.html

CREATE TABLE secondary_uuid
(
    id BINARY(16) NOT NULL,
    INDEX idx (id)
) Engine=InnoDB;