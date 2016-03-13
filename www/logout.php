<?php
	session_start();
	session_destroy();
	session_regenerate_id();
	if(isset($_GET["viaExtension"])) {
		header("Location: /signin.php?viaExtension=true");
	} else {
		header("Location: /index.php");
	}
?>