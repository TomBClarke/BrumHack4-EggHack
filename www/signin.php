<?php 
	session_start(); 
	if(isset($_SESSION['user'])) {
		redirect();
	}

	include( "resources/web/sql.php"); 
	$error_signin = false;

	if (isset($_POST["username"])) {
		$result = loadUser($_POST["username"], $_POST["password"]);

	    if (!$result or mysqli_num_rows($result) == 0) {
	    	$error_signin = true;
	    } else {
		    $row = mysqli_fetch_assoc($result);
		    session_regenerate_id();
		    $_SESSION['user'] = $row;
		    redirect();
	  	}
	}

	function redirect() {
		if (isset($_GET["viaExtension"])) {
			header("Location: /extension.php");
		} else {
			header("Location: /index.php");
		}
	}
?>

<?php include("resources/web/meta.php"); ?>

<!DOCTYPE html>
<html>

<head>
    <title>Egg Hack - Sign in</title>
    <link type="text/css" rel="stylesheet" href="resources/web/main.css" />
</head>

<body>
    <img src="resources/img/EggHack.png" />
    <h1>Sign in to EggHack</h1>

    <form action="signin.php" method="post" id="form-signin">
	    <?php if ($error_signin) {?>
	    <h2>Error signing-in</h2>
	    <?php } ?>

	    <h2 class="form-signin-heading">Registration</h2> Username:
	    <input name="username" type="text" placeholder="Username"> Password:
	    <input name="password" type="password" placeholder="Password">

	    <button type="submit">Sign in</button>
	</form>

	<?php if (isset($_GET["viaExtension"]) and $_GET["viaExtension"] === true) { ?>
	<p>Need an account? <a target="_blank" href="index.php">Sign up</a></p>
	<?php } else { ?>
	<p>Need an account? <a href="index.php">Sign up</a></p>
	<?php } ?>
	<div id="bottom">By <a href="http://tombclarke.co.uk">Tom Clarke</a>, Cameron Angus and Rowan Cole</div>
</body>

</html>