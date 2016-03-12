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

// returns { eggid: 0, location: google.com, value: 1, tos: [id, id, id...] }
function getGottenEggs(userID) {
	connect();

	$sql = "SELECT F.eggid FROM found F WHERE F.userid = " . userID;
	$sql = "SELECT E.eggid, E.location, E.value FROM eggs E WHERE E.eggid IN ( " . $sql . " ) ORDER BY E.eggid;";

	$result = mysql_query($sql, $db);

    if(!$result)
    	die("Query failed: " . mysql_error());

    $json = "";
    while($row = mysql_fetch_array($result)) {
		$json = $json . "{eggid:" . $row['eggid'] . ",location:" . $row['location'] . ",value:" . $row['value'];

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