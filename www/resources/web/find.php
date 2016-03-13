<?php 
	session_start();
	include( "resources/web/sql.php"); 

	if(isset($_SESSION['user']) and isset($_POST["eggid"])) {
		echo setFound($_SESSION['user']['userid'], $_POST["eggid"]);
	} else {
		echo "fail";
	}
?>