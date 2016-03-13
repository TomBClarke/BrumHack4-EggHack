<?php 
	session_start(); 
	if(!isset($_SESSION['user']) || !isset($_GET['website']))
		header("Location: /index.php");

    $result = getegg($_SESSION['user']['userid'], $_GET['website']);

    if (!$result or mysqli_num_rows($result) == 0)
        header("Location: /index.php");

    $row = mysqli_fetch_array($result);

	include( "resources/web/sql.php");
?>

<?php include("resources/web/meta.php"); ?>

<!DOCTYPE html>
<html>

<head>
    <title>Egg Hack - Riddle</title>
    <link type="text/css" rel="stylesheet" href="resources/web/main.css" />
</head>

<body>
    <img src="resources/img/EggHack.png" />
    
    <h1>Congratulations!</h1>
    <h2>You found <?php echo $row['location']; ?> and earn't <?php echo $row['value']; ?> points!</h2>
    <h2>Your next riddle is...</h2>
    <h3><?php echo $row['riddle']; ?></h3>
</body>

</html>