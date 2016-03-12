CREATE TABLE user  (
	userid INTEGER NOT NULL, 
	username VARCHAR(40) NOT NULL UNIQUE,
	name VARCHAR(40) NOT NULL, 
	password VARCHAR(20) NOT NULL, 
	public BOOLEAN NOT NULL, 
	PRIMARY KEY (userid)
);

CREATE TABLE eggs (
	eggid INTEGER NOT NULL, 
	location VARCHAR(100) NOT NULL, 
	redirect VARCHAR(40) NOT NULL, 
	value INTEGER, 
	PRIMARY KEY (eggid)
);

CREATE TABLE egglinks (
	eggfrom INTEGER NOT NULL, 
	eggto INTEGER NOT NULL, 
	FOREIGN KEY (eggfrom) REFERENCES eggs(eggid), 
	FOREIGN KEY (eggto) REFERENCES eggs(eggid)
);

CREATE TABLE found (
	userid INTEGER NOT NULL,
	eggid INTEGER NOT NULL,
	foundat DATETIME NOT NULL,
	FOREIGN KEY (userid) REFERENCES user(userid), 
	FOREIGN KEY (eggid) REFERENCES eggs(eggid)
);