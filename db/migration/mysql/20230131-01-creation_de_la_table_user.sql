CREATE TABLE user (
    ref VARCHAR(36) NOT NULL PRIMARY KEY,
    entity varchar(10) NOT NULL,
    email VARCHAR(320) NOT NULL,
    CONSTRAINT UNIQUE entity_email_u (entity, email),
    pass VARCHAR(256) NOT NULL,
    salt VARCHAR(36) NOT NULL,
    enable TINYINT NOT NULL DEFAULT 0 CHECK (enable = 0 OR enable = 1),
    enable_token VARCHAR(36) NOT NULL,
    enable_limit DATETIME NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
