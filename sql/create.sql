CREATE DATABASE egghack;
USE egghack

DROP TABLE found;
DROP TABLE egglinks;
DROP TABLE eggs;
DROP TABLE user;

CREATE TABLE user (
	userid INTEGER NOT NULL AUTO_INCREMENT, 
	username VARCHAR(40) NOT NULL UNIQUE,
	name VARCHAR(40) NOT NULL, 
	password VARCHAR(20) NOT NULL, 
	public BOOLEAN NOT NULL, 
	PRIMARY KEY (userid)
);

CREATE TABLE eggs (
	eggid INTEGER NOT NULL AUTO_INCREMENT, 
	location VARCHAR(100) NOT NULL,
	riddle VARCHAR(100000000) NOT NULL,
	value INTEGER, 
	PRIMARY KEY (eggid),
	CHECK (value > 0)
);

CREATE TABLE egglinks (
	eggfrom INTEGER NOT NULL, 
	eggto INTEGER NOT NULL, 
	FOREIGN KEY (eggfrom) REFERENCES eggs(eggid), 
	FOREIGN KEY (eggto) REFERENCES eggs(eggid),
	PRIMARY KEY (eggfrom, eggto)
);

CREATE TABLE found (
	userid INTEGER NOT NULL,
	eggid INTEGER NOT NULL,
	foundat DATETIME NOT NULL,
	FOREIGN KEY (userid) REFERENCES user(userid), 
	FOREIGN KEY (eggid) REFERENCES eggs(eggid),
	PRIMARY KEY (userid, eggid)
);

-- Testing
INSERT INTO user VALUES (default, "tom", "tom", "password123", 1);
INSERT INTO user VALUES (default, "rowan", "rowan", "password123", 1);
INSERT INTO eggs VALUES (default, "google.com", "basically the home page of the internet", 1);
INSERT INTO eggs VALUES (default, "reddit.com", "the home page of the internet", 2);
INSERT INTO egglinks VALUES (1, 2);
INSERT INTO found VALUES (1, 1, now());
SELECT E.eggid
FROM eggs E
WHERE E.eggid in (
 SELECT L.eggto
 FROM egglinks L
 WHERE L.eggfrom IN (
 SELECT F.eggid
 FROM found F
 WHERE F.userid = 1
 )
);