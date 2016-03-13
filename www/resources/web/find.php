<?php 
	session_start();
	include( "sql.php"); 

	if(isset($_SESSION['user']) and isset($_POST["eggid"])) {
		echo setFound($_SESSION['user']['userid'], filterURL($_POST["website"]));
	} else {
		echo "fail";
	}

	function filterURL($input) {
		return $input;
	}
?>