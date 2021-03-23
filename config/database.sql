CREATE SCHEMA IF NOT EXISTS `camagru`;

USE camagru;

CREATE TABLE IF NOT EXISTS users (
    id			    INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    login		    CHAR(20) NOT NULL UNIQUE,
    email		    CHAR(50) NOT NULL UNIQUE,
    `name`		    VARCHAR(30) NOT NULL,
    surname		    VARCHAR(30) NOT NULL,
    password	    VARCHAR(255) NOT NULL,
    token           VARCHAR(255),
    activated       BOOLEAN DEFAULT '0',
    photo           LONGBLOB,
    notification    BOOLEAN DEFAULT '0',
    created_at	    TIMESTAMP);

CREATE TABLE IF NOT EXISTS photos (
	id			INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	user_id		INT,
	photo       LONGBLOB NOT NULL,
	description	TINYTEXT,
	created_at	TIMESTAMP,
	FOREIGN KEY (user_id)  REFERENCES users (id));

CREATE TABLE IF NOT EXISTS comments (
	id			    INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	photo_id	    INT ,
	user_id		    INT ,
	comment_text    TINYTEXT,
	created_at	    TIMESTAMP,
	FOREIGN KEY (user_id)  REFERENCES users (id),
	FOREIGN KEY (photo_id)  REFERENCES photos (id));

CREATE TABLE IF NOT EXISTS likes (
	id			INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	photo_id	INT,
	user_id		INT,
	created_at	TIMESTAMP,
    FOREIGN KEY (user_id)  REFERENCES users (id),
	FOREIGN KEY (photo_id)  REFERENCES photos (id));

CREATE TABLE IF NOT EXISTS reset_password (
    id			INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    user_id		INT,
    token       VARCHAR(255),
    created_at	TIMESTAMP,
    FOREIGN KEY (user_id)  REFERENCES users (id));

