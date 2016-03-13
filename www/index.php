<?php 
	session_start(); 
	if(isset($_SESSION['user']) and isset($_GET["viaExtension"]) and $_GET["viaExtension"] === true) {
		header("Location: /extension.php");
	}

	include( "resources/web/sql.php"); 
	$loggedin = isset($_SESSION['user']);
	$error_signup = false;

	if (!$loggedin and isset($_POST["username"]) and isset($_POST["name"]) and isset($_POST["password"]) and isset($_POST["confirm"])) {
		$user = $_POST["username"];
		$name = $_POST["name"];
		$pwd = $_POST["password"];
		$confirm = $_POST["confirm"];
		$visible =  isset($_POST["visible"]) ? 1 : 0;

		if ($pwd == "" || $pwd != $confirm) {
			$error_signup = true;
		} else {
			$error_signup = !createUser($user, $name, $pwd, 1);
		}
	}
?>

<?php include("resources/web/meta.php"); ?>

<!DOCTYPE html>
<html>

<head>
    <title>Egg Hack</title>
    <link type="text/css" rel="stylesheet" href="resources/web/main.css" />
</head>

<body>
    <img src="resources/img/EggHack.png" />
    <h1>Welcome to EggHack</h1>

    <!-- Sign up/get extension -->
    <?php if (!$loggedin) {?>
    <form action="index.php" method="post" id="form-signup">
        <?php if ($error_signup) {?>
        <h2>Error signing-up</h2>
        <?php } ?>

        <h2 class="form-signin-heading">Registration</h2> Username:
        <input name="username" type="text" placeholder="Username"> Name:
        <input name="name" type="text" placeholder="Name"> Password:
        <input name="password" type="password" placeholder="Password"> Confirm Password:
        <input name="confirm" type="password" placeholder="Confirm Password">
        <input name="visible" type="checkbox" checked>Make my score public

        <button type="submit">Sign up</button>
    </form>

    <h2>Or <span>sign in</span> and download the <a href="/">extension</a></h2>
    <?php } else { ?>
    <?php if(isset($_POST["signupsuccess"]) and $_POST[ "signupsuccess"]=== true) ?>
    <h2>To begin download our <a href="/">extension</a> and begin the hunt!</h2>
    <h2>Check your <a href="/myprogress">stats</a>.</h2>
    <a href="logout.php">Logout</a>
    <?php } ?>
</body>

</html>