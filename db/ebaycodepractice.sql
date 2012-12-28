DROP DATABASE IF EXISTS ebaycodepractice;
CREATE DATABASE ebaycodepractice;
USE ebaycodepractice;

DROP TABLE IF EXISTS users;
CREATE TABLE users(
  `id` INT(16) AUTO_INCREMENT,
  `hash` VARCHAR (32) UNIQUE,
  `timeCreate` VARCHAR (32),
  `timeLogin` VARCHAR (32),
  `userName` VARCHAR (255) UNIQUE,
  `email` VARCHAR (255) UNIQUE,
  `password` VARCHAR (255),
  PRIMARY KEY(id)
);

DROP TABLE IF EXISTS groups;
CREATE TABLE groups(
  `id` INT(16) AUTO_INCREMENT,
  `name` VARCHAR (255),
  PRIMARY KEY(id),
  KEY(name)
);

DROP TABLE IF EXISTS roles;
CREATE TABLE roles(
  `id` INT(16) AUTO_INCREMENT,
  `name` VARCHAR (255),
  PRIMARY KEY(id)
);

DROP TABLE IF EXISTS userGroups;
CREATE TABLE userGroups(
  `gid` INT(16),
  `uid` INT(16),
  FOREIGN KEY (gid) REFERENCES groups(id),
  FOREIGN KEY (uid) REFERENCES users(id)
);

DROP TABLE IF EXISTS userRoles;
CREATE TABLE userRoles(
  `rid` INT(16),
  `uid` INT(16),
  FOREIGN KEY (rid) REFERENCES roles(id),
  FOREIGN KEY (uid) REFERENCES users(id)
);

DROP TABLE IF EXISTS groupRoles;
CREATE TABLE groupRoles(
  `gid` INT(16),
  `rid` INT(16),
  FOREIGN KEY (rid) REFERENCES roles(id),
  FOREIGN KEY (gid) REFERENCES groups(id)
);



INSERT INTO users (
  `hash`,
  `timeCreate`,
  `timeLogin`,
  `userName`,
  `email`,
  `password`
) VALUES (
  "1234567890",
  "12345678",
  "12345678",
  "supersudo",
  "damanshia@ebay.com",
  "5f4dcc3b5aa765d61d8327deb882cf99"
);

INSERT INTO roles(
  `id`,
  `name`
) VALUES (
  "1",
  "Administrator"
);

INSERT INTO userRoles(
  `rid`,
  `uid`
) VALUES (
  "1",
  "1"
);