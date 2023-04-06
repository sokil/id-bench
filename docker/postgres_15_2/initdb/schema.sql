CREATE TABLE primary_autoincrement
(
    id BIGINT NOT NULL PRIMARY KEY GENERATED ALWAYS AS IDENTITY
);

CREATE TABLE primary_uuid
(
    id UUID NOT NULL PRIMARY KEY
);

CREATE TABLE secondary_uuid
(
    id UUID NOT NULL
);

CREATE INDEX idx ON secondary_uuid (id);