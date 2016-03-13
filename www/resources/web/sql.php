<?php

$db = null;

function connect() {
	$db = null;

	$db = mysql_connect("localhost", "egghack");
	if(!$db)
		die("Couldn't connect to the MySQL server.");

	$use = mysql_select_db("egghack", $db);
	if(!$use)
		die("Couldn't select database.");
}

// create user
function createUser($user, $name, $pwd, $visible) {
	connect();

	$sql = sprintf("INSERT INTO user VALUES (default, '%s', '%s', '%s', '%b');",
		mysql_real_escape_string($user),
		mysql_real_escape_string($name),
		mysql_real_escape_string($pwd),
		$visible
	);

	$result = mysql_query($sql, $db);

    mysql_close($db);

	if(!$result)
		return false;

	return true;
}

// login user
function loadUser($user, $password) {
	connect();

	$sql = sprintf("SELECT U.userid, U.username, U.name FROM user U WHERE U.username = '%s' AND U.password = '%s';",
		mysql_real_escape_string($user),
		mysql_real_escape_string($password)
	);

	$result = mysql_query($sql, $db);

    mysql_close($db);

	return $result;
}

// gets the users points
function getUserPoints($userid) {
	connect();

	$sql = sprintf("SELECT sum(E.value) AS total FROM eggs E WHERE E.eggid in (SELECT F.eggid FROM found F WHERE F.userid = '%d');",
		mysql_real_escape_string($userid)
	);

	$result = mysql_query($sql, $db);

	mysql_close($db);

	if(!$result)
    	return 0;

    return $row['total'];
}

// 0 if website is not valid, value of egg otherwise
function getegg($userid, $site) {
	connect();

	$sql = sprintf("SELECT E.location, E.value FROM eggs E WHERE E.eggid in (SELECT L.eggto FROM egglinks L WHERE L.eggfrom IN (SELECT F.eggid FROM found F WHERE F.userid = '%d'));",
		mysql_real_escape_string($userid)
	);

	$result = mysql_query($sql, $db);

	mysql_close($db);

	if(!$result)
    	return 0;

    while($row = mysql_fetch_array($result)) {
    	if ($site == $row['location'])
    		return $row['value'];
    }

    return 0;
}

// set found
function setFound($userid, $eggid) {
	connect();

	$sql = sprintf("SELECT L.eggto FROM egglinks L WHERE L.eggfrom IN (SELECT F.eggid FROM found F WHERE F.userid = '%d');",
		mysql_real_escape_string($userid)
	);

	$result = mysql_query($sql, $db);

	if(!$result)
    	return "fail";

    $matching = false;
    while($row = mysql_fetch_array($result)) {
		if ($eggid == $row['eggto'])
			$matching = true;
    }

    if (!$matching)
    	return "fail";

	$sql = sprintf("INSERT INTO found VALUES ('%d', '%d', now());",
		mysql_real_escape_string($userid),
		mysql_real_escape_string($eggid)
	);

	$result = mysql_query($sql, $db);

    if(!$result)
    	return "fail";

    mysql_close($db);

    $sql = sprintf("SELECT E.riddle FROM eggs E WHERE E.eggid = '%d';",
		mysql_real_escape_string($eggid)
	);

	$result = mysql_query($sql, $db);

    if(!$result)
    	return "GENUINE ERROR";

    mysql_close($db);

    $row = mysql_fetch_array($result);
    return $row['riddle'];
}

// returns { eggid: 0, location: google.com, value: 1, tos: [id, id, id...] }
function getGottenEggs($userID) {
	connect();

	$sql = sprintf("SELECT F.eggid FROM found F WHERE F.userid = '$d'", 
		mysql_real_escape_string($userID)
	);
	$sql = "SELECT E.eggid, E.location, E.riddle, E.value FROM eggs E WHERE E.eggid IN ( " . $sql . " ) ORDER BY E.eggid;";

	$result = mysql_query($sql, $db);

    if(!$result)
    	die("Query failed: " . mysql_error());

    $json = "";
    while($row = mysql_fetch_array($result)) {
		$json = $json . "{id:" . $row['eggid'] . ",location:" . $row['location'] . ",riddle:" . $row['riddle'] . ",value:" . $row['value'];

    	$sql = "SELECT L.eggto FROM egglinks L WHERE L.getfrom = " . $row['eggid'] . "ORDER BY L.eggto;";
    	$rresult = mysql_query($sql, $db);

    	if(!$rresult)
	    	die("Query failed: " . mysql_error());

	    $tos = "";
	    while($rrow = mysql_fetch_array($rresult)) {
	    	$tos = $tos . $rrow['eggot'] . ",";
	    }
	    $tos = rtrim($tos, ",");
	    $tos = "[" . $tos . "]";

	    $json = $json . ",tos:" . $tos . "},";
    }
    $json = rtrim($json, ",");
	$json = "[" . $json . "]";

	mysql_close($db);

	return $json;
}
?>