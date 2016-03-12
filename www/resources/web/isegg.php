<?php 
	session_start();
	include( "resources/web/sql.php"); 

	if(isset($_SESSION['user']) and isset($_POST["userid"]) and isset($_POST["eggid"])) {
		echo getegg();
	} else {
		echo 0;
	}
?>