alter table user
    add column session_private_token VARCHAR(256);
---
alter table user
    add column session_public_token VARCHAR(256);
---
alter table user
    add column session_expiration TIMESTAMP;
