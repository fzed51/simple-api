ALTER TABLE entity
    CHANGE COLUMN owner client VARCHAR(256) NOT NULL ;
---
ALTER TABLE entity
    DROP INDEX idx_owner;
---
ALTER TABLE entity
    ADD INDEX idx_client (client ASC);
---
ALTER TABLE user
    CHANGE COLUMN owner client VARCHAR(256) NOT NULL ;
---
ALTER TABLE user
    DROP INDEX idx_owner;
---
ALTER TABLE user
    ADD INDEX idx_client (client ASC);
