<?php 
	session_start();
	include( "sql.php"); 

	if(isset($_SESSION['user']) and isset($_POST["website"])) {
		$isvalidegg = isegg($_SESSION['user']['userid'], filterURL($_POST["website"]));
		echo $isvalidegg and !beenFound($_SESSION['user']['userid'], filterURL($_POST["website"]));
	} else {
		echo 0;
	}
?>