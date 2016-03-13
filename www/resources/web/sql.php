<?php

function connect() {
	$db = mysqli_connect("localhost", "root", "correcthorsebatterystaple", "egghack");
	//$db = mysqli_connect("localhost", "root", "", "egghack");
	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	}

	return $db;
}

// create user
function createUser($user, $name, $pwd, $visible) {
	$db = connect();

	$sql = sprintf("INSERT INTO user VALUES (default, '%s', '%s', '%s', " . $visible . ");",
		mysqli_real_escape_string($db, $user),
		mysqli_real_escape_string($db, $name),
		mysqli_real_escape_string($db, $pwd)
	);

	echo $sql;

	$result = mysqli_query($db, $sql);

    mysqli_close($db);

	if(!$result)
		return false;

	return true;
}

// login user
function loadUser($user, $password) {
	$db = connect();

	$sql = sprintf("SELECT U.userid, U.username, U.name FROM user U WHERE U.username = '%s' AND U.password = '%s';",
		mysqli_real_escape_string($db, $user),
		mysqli_real_escape_string($db, $password)
	);

	echo $sql;

	$result = mysqli_query($db, $sql);

    mysqli_close($db);

	return $result;
}

// gets the users points
function getUserPoints($userid) {
	$db = connect();

	$sql = sprintf("SELECT sum(E.value) AS total FROM eggs E WHERE E.eggid in (SELECT F.eggid FROM found F WHERE F.userid = '%d');",
		mysqli_real_escape_string($db, $userid)
	);

	echo $sql;

	$result = mysqli_query($db, $sql);

	mysqli_close($db);

	if(!$result)
    	return 0;

    return $row['total'];
}

// 0 if website is not valid, value of egg otherwise
function getegg($userid, $site) {
	$db = connect();

	$sql = sprintf("SELECT E.location, E.value FROM eggs E WHERE E.eggid in (SELECT L.eggto FROM egglinks L WHERE L.eggfrom IN (SELECT F.eggid FROM found F WHERE F.userid = '%d'));",
		mysqli_real_escape_string($db, $userid)
	);

	echo $sql;

	$result = mysqli_query($db, $sql);

	mysqli_close($db);

	if(!$result)
    	return 0;

    while($row = mysqli_fetch_array($result)) {
    	if ($site == $row['location'])
    		return $row['value'];
    }

    return 0;
}

// set found
function setFound($userid, $eggid) {
	$db = connect();

	$sql = sprintf("SELECT L.eggto FROM egglinks L WHERE L.eggfrom IN (SELECT F.eggid FROM found F WHERE F.userid = '%d');",
		mysqli_real_escape_string($db, $userid)
	);

	echo $sql;

	$result = mysqli_query($db, $sql);

	if(!$result)
    	return "fail";

    $matching = false;
    while($row = mysqli_fetch_array($result)) {
		if ($eggid == $row['eggto'])
			$matching = true;
    }

    if (!$matching)
    	return "fail";

	$sql = sprintf("INSERT INTO found VALUES ('%d', '%d', now());",
		mysqli_real_escape_string($db, $userid),
		mysqli_real_escape_string($db, $eggid)
	);

	echo $sql;

	$result = mysqli_query($db, $sql);

    if(!$result)
    	return "fail";

    mysqli_close($db);

    $sql = sprintf("SELECT E.riddle FROM eggs E WHERE E.eggid = '%d';",
		mysqli_real_escape_string($db, $eggid)
	);

	echo $sql;

	$result = mysqli_query($db, $sql);

    if(!$result)
    	return "GENUINE ERROR";

    mysqli_close($db);

    $row = mysqli_fetch_array($result);
    return $row['riddle'];
}

// returns { eggid: 0, location: google.com, value: 1, tos: [id, id, id...] }
function getGottenEggs($userID) {
	$db = connect();

	$sql = sprintf("SELECT F.eggid FROM found F WHERE F.userid = '$d'", 
		mysqli_real_escape_string($db, $userID)
	);
	$sql = "SELECT E.eggid, E.location, E.riddle, E.value FROM eggs E WHERE E.eggid IN ( " . $sql . " ) ORDER BY E.eggid;";

	echo $sql;

	$result = mysqli_query($db, $sql);

    if(!$result)
    	die("Query failed: " . $db->error);

    $json = "";
    while($row = mysqli_fetch_array($result)) {
		$json = $json . "{id:" . $row['eggid'] . ",location:" . $row['location'] . ",riddle:" . $row['riddle'] . ",value:" . $row['value'];

    	$sql = "SELECT L.eggto FROM egglinks L WHERE L.getfrom = " . $row['eggid'] . "ORDER BY L.eggto;";
    	$rresult = mysqli_query($db, $sql);

    	if(!$rresult)
	    	die("Query failed: " . $db->error);

	    $tos = "";
	    while($rrow = mysqli_fetch_array($rresult)) {
	    	$tos = $tos . $rrow['eggot'] . ",";
	    }
	    $tos = rtrim($tos, ",");
	    $tos = "[" . $tos . "]";

	    $json = $json . ",tos:" . $tos . "},";
    }
    $json = rtrim($json, ",");
	$json = "[" . $json . "]";

	mysqli_close($db);

	return $json;
}
?>