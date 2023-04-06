CREATE TABLE primary_autoincrement
(
    id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT
) Engine=InnoDB;

CREATE TABLE primary_uuid
(
    id BINARY(16) NOT NULL PRIMARY KEY
) Engine=InnoDB;

CREATE TABLE secondary_uuid
(
    id BINARY(16) NOT NULL,
    INDEX idx (id)
) Engine=InnoDB;