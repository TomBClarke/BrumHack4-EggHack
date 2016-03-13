<?php

$db = null;

function connect() {
	$db = null;

	$db = mysql_connect("127.0.0.1", "NAME");
	if(!$db)
		die("Couldn't connect to the MySQL server.");

	$use = mysql_select_db("NAME", $db);
	if(!$use)
		die("Couldn't select database.");
}

// create user
function createUser(user, name, pwd, visible) {
	connect();

	$sql = sprintf("INSERT INTO user VALUES (default, '%s', '%s', '%s', '%b');",
		mysql_real_escape_string($user),
		mysql_real_escape_string($name),
		mysql_real_escape_string($pwd),
		$visible
	);

	$result = mysqli_query($db, $sql);

	if(!$result)
    	die("Query failed: " . mysql_error());
    mysqli_close($db);

    // set up user i guess
}

// set found
function setFound(userid, eggid) {
	connect();

	$sql = sprintf("INSERT INTO found VALUES ('%d', '%d', now());",
		mysql_real_escape_string($userid),
		mysql_real_escape_string($eggid)
	);

	$result = mysqli_query($db, $sql);

	if(!$result)
    	die("Query failed: " . mysql_error());
    mysqli_close($db);

    // maybbe return true or false or some shit
}

// returns { eggid: 0, location: google.com, value: 1, tos: [id, id, id...] }
function getGottenEggs($userID) {
	connect();

	$sql = sprintf("SELECT F.eggid FROM found F WHERE F.userid = '$d'", 
		mysql_real_escape_string($userID)
	);
	
	$sql = "SELECT E.eggid, E.location, E.value FROM eggs E WHERE E.eggid IN ( " . $sql . " ) ORDER BY E.eggid;";

	$result = mysqli_query($db, $sql);

    if(!$result)
    	die("Query failed: " . mysql_error());

    $json = "";
    while($row = mysql_fetch_array($result)) {
		$json = $json . "{id:" . $row['eggid'] . ",location:" . $row['location'] . ",value:" . $row['value'];

    	$sql = "SELECT L.eggto FROM egglinks L WHERE L.getfrom = " . $row['eggid'] . "ORDER BY L.eggto;";
    	$rresult = mysqli_query($db, $sql);

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

	mysqli_close($db);

	return $json;
}
?>