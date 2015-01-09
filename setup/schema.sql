CREATE TABLE repo_list (
    id          INTEGER NOT NULL PRIMARY KEY,
    name        VARCHAR(256),
    user        VARCHAR(256),
    lang        VARCHAR(256),
    readme_html BLOB,
    rank        INTEGER
);