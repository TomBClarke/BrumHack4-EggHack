<?php 
	session_start(); 
	if(!isset($_SESSION['user']) || !isset($_GET['website']))
		header("Location: /index.php");

    include( "resources/web/sql.php");

    $result = geteggfull($_SESSION['user']['userid'], $_GET['website']);

    if (!$result)
        header("Location: /index.php");
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
    <h2>You found <?php echo $result['location']; ?> and earned <?php echo $result['value']; ?> points!</h2>
    <h2>Your next riddle is...</h2>
    <h3><?php echo $result['riddle']; ?></h3>
    <div id="bottom">By <a href="http://tombclarke.co.uk">Tom Clarke</a>, Cameron Angus and Rowan Cole</div>
</body>

</html>