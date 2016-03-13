<?php 
	session_start();

	if(!isset($_SESSION['user'])) {
		header("Location: /signin.php?viaExtension=true");
	}

	include( "resources/web/sql.php"); 
?>

<?php include("resources/web/meta.php"); ?>

<!DOCTYPE html>
<html>

<head>
    <link type="text/css" rel="stylesheet" href="resources/web/main.css" />
</head>

<body>

<?php echo "Logged in as: " . $_SESSION['user']['name'] . " (" . $_SESSION['user']['username'] . ")"; ?>
<a href="logout.php?viaExtension=true">Logout</a>
<div id="bottom">By <a href="http://tombclarke.co.uk">Tom Clarke</a>, Cameron Angus and Rowan Cole</div>

</body>

</html>