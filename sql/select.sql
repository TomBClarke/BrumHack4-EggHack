-- Get found eggs
SELECT E.eggid, E.location, E.value
FROM eggs E
WHERE E.eggid IN (
	SELECT F.eggid
	FROM found F
	WHERE F.userid = userid
)
ORDER BY E.eggid;

-- Get eggs to
SELECT L.eggto
FROM egglinks L
WHERE L.getfrom = eggid
ORDER BY L.eggto;

-- Get next eggs
SELECT E.eggid
FROM eggs E
WHERE E.eggid in (
	SELECT L.eggto
	FROM egglinks L
	WHERE L.eggfrom IN (
		SELECT F.eggid
		FROM found F
		WHERE F.userid = userid
	)
);

-- Get all eggs
SELECT E.eggid
FROM eggs E;

-- Get user (login)
SELECT U.userid, U.username, U.name, U.public
FROM user U
WHERE U.username = username AND U.password = password;

-- Get riddle
SELECT E.riddle
FROM eggs E
WHERE E.eggid = eggid AND
	E.eggid IN (
	SELECT F.eggid
	FROM found F
	WHERE F.userid = userid
);

-- Get total earned
SELECT SUM(E.value)
FROM eggs E
WHERE E.eggid IN (
	SELECT F.eggid
	FROM found F
	WHERE F.userid = userid
);