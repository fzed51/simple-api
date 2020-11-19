CREATE TABLE user
(
    ref       VARCHAR(256) NOT NULL,
    owner     VARCHAR(256) NOT NULL,
    name      VARCHAR(256) NOT NULL,
    email     VARCHAR(512) NOT NULL,
    pass      VARCHAR(256) NOT NULL,
    role      JSON         NOT NULL,
    created   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated   TIMESTAMP    NULL,
    PRIMARY KEY (ref),
    INDEX idx_owner (owner ASC),
    UNIQUE INDEX idx_name (name ASC),
    UNIQUE INDEX idx_email (email ASC)
)
DEFAULT CHARACTER SET = utf8;