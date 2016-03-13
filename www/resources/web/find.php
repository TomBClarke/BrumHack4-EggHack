<?php 
	session_start();
	include( "sql.php"); 

	if(isset($_SESSION['user']) and isset($_POST["website"])) {
		echo setFound($_SESSION['user']['userid'], filterURL($_POST["website"]));
	} else {
		echo "fail";
	}
?>