CREATE TABLE entity
(
    ref       VARCHAR(256) NOT NULL,
    owner     VARCHAR(256) NOT NULL,
    ressource VARCHAR(256) NOT NULL,
    data      JSON         NOT NULL,
    created   TIMESTAMP    NULL DEFAULT CURRENT_TIMESTAMP,
    updated   TIMESTAMP    NULL,
    PRIMARY KEY (ref),
    INDEX idx_owner (owner ASC),
    INDEX idx_ressource (ressource ASC)
);