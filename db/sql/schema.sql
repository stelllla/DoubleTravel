CREATE TABLE IF NOT EXISTS "USERS_" (
ID                              INTEGER             PRIMARY KEY,
PASS                            VARCHAR2(255)       NOT NULL,
EMAIL                           VARCHAR2(255)       NOT NULL,
FIRST_NAME                      VARCHAR2(255)       NOT NULL,
LAST_NAME                       VARCHAR2(255)       NOT NULL,

UNIQUE (EMAIL)
);
