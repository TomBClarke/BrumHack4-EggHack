<?php

function filterURL($input) {
	$output = str_replace("www.", "", $input);
	return $output;
}

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

	$result = mysqli_query($db, $sql);

    mysqli_close($db);

	if(!$result)
		return false;

	$userdetails = loadUser($user, $pwd);

	$db = connect();

	$sql = "INSERT INTO found VALUES (" . mysqli_fetch_assoc($userdetails)['userid'] . ", 1, now());";

	$result = mysqli_query($db, $sql);

    mysqli_close($db);
	return true;
}

function beenFound($userid, $website) {
	$db = connect();

	$sql = "SELECT E.location FROM eggs E WHERE E.eggid in (SELECT F.eggid FROM found F WHERE F.userid = " . $userid . ");";

	$result = mysqli_query($db, $sql);

	mysqli_close($db);

	if(!$result)
    	return 0;

    while($row = mysqli_fetch_array($result)) {
    	if ($website == $row['location'])
    		return 1;
    }

    return 0;
}

// login user
function loadUser($user, $password) {
	$db = connect();

	$sql = sprintf("SELECT U.userid, U.username, U.name FROM user U WHERE U.username = '%s' AND U.password = '%s';",
		mysqli_real_escape_string($db, $user),
		mysqli_real_escape_string($db, $password)
	);

	$result = mysqli_query($db, $sql);

    mysqli_close($db);

	return $result;
}

// gets the users points
function getUserPoints($userid) {
	$db = connect();

	$sql = "SELECT sum(E.value) AS total FROM eggs E WHERE E.eggid in (SELECT F.eggid FROM found F WHERE F.userid = " . $userid . ");";

	$result = mysqli_query($db, $sql);

	mysqli_close($db);

	if(!$result)
    	return 0;

    return $row['total'];
}

// 0 if website is not valid, value of egg otherwise
function getegg($userid, $site) {
	$db = connect();

	$sql = "SELECT E.location, E.value, E.riddle FROM eggs E WHERE E.eggid in (SELECT F.eggid FROM found F WHERE F.userid = " . $userid . ");";

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

// 0egg details
function geteggfull($userid, $site) {
	$db = connect();

	$sql = "SELECT E.location, E.value, E.riddle FROM eggs E WHERE E.eggid in (SELECT F.eggid FROM found F WHERE F.userid = " . $userid . ");";

	$result = mysqli_query($db, $sql);

	mysqli_close($db);

	if(!$result)
    	return 0;

    while($row = mysqli_fetch_array($result)) {
    	if ($site == $row['location'])
    		return $row;
    }

    return 0;
}

// see's if egg is valid (to)
function isegg($userid, $website) {
	$db = connect();

	// get list of tos
	// see if it is in the list of tos
	$sql = "SELECT E.location, E.eggid FROM eggs E WHERE E.eggid IN (SELECT L.eggto FROM egglinks L WHERE L.eggfrom IN (SELECT F.eggid FROM found F WHERE F.userid = " . $userid . "));";

	$result = mysqli_query($db, $sql);

	if(!$result || mysqli_num_rows($result) == 0) {
	    mysqli_close($db);
    	return 0;
	}

    $eggid = 0;
	while($row = mysqli_fetch_array($result)) {
    	if ($website == $row['location'])
    		$eggid = $row['eggid'];
    }

    if ($eggid == 0) {
	    mysqli_close($db);
	    return 0;
    }

    // list of tos I have
    $sql = "SELECT L.eggto FROM egglinks L WHERE L.eggto = " . $eggid . " AND L.eggfrom IN (SELECT F.eggid FROM found F WHERE F.userid = " . $userid . ");";
	$result = mysqli_query($db, $sql);
	$myLength = mysqli_num_rows($result);

	// list of tos in full
	$sql = "SELECT L.eggto FROM egglinks L WHERE L.eggto = " . $eggid . ";";
	$result = mysqli_query($db, $sql);
	$fullLength = mysqli_num_rows($result);

	mysqli_close($db);

	// if equal then we've found all froms so can get the to
	if ($myLength == $fullLength) {
		return 1;
	} else {
		return 0;
	}
}

// set found
function setFound($userid, $website) {
	$canDoIt = isegg($userid, $website);

	if ($canDoIt == 0)
    	return "fail";

	$db = connect();

	$sql = sprintf("SELECT E.eggid FROM eggs E WHERE E.location = '%s';",
		mysqli_real_escape_string($db, $website)
	);
	$result = mysqli_query($db, $sql);
	if(!$result)
    	return "fail";

	$sql = "INSERT INTO found VALUES (" . $userid . "," . mysqli_fetch_array($result)['eggid'] . ", now());";
	$result = mysqli_query($db, $sql);

    if(!$result)
    	return "fail";

    mysqli_close($db);
    return "success";
}

// returns { eggid: 0, location: google.com, value: 1, tos: [id, id, id...] }
function getGottenEggs($userID) {
	$db = connect();

	$sql = "SELECT F.eggid FROM found F WHERE F.userid = " . $userID . ";";
	$sql = "SELECT E.eggid, E.location, E.riddle, E.value FROM eggs E WHERE E.eggid IN ( " . $sql . " ) ORDER BY E.eggid;";

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