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

        // This doesn't work but ah well
        if (isset($_POST["visible"])) {
	        $visible =  1;
            echo $_POST["visible"] ;
        } else {
            $visible =  0;
        }

		if ($user == "" || $name == "" || $pwd == "" || $pwd != $confirm) {
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
        <input name="username" type="text" placeholder="Username"><br/> Name:
        <input name="name" type="text" placeholder="Name"><br/> Password:
        <input name="password" type="password" placeholder="Password"><br/> Confirm Password:
        <input name="confirm" type="password" placeholder="Confirm Password">
        <input name="visible" type="checkbox" checked><br/>Make my score public

        <button type="submit">Sign up</button>
    </form>

    <h2>Or <a href="signin.php">sign in</a> and download the <a href="http://54.84.108.88/resources/extension.crx" download>extension</a></h2>
    <?php } else { ?>
    <?php if(isset($_POST["signupsuccess"]) and $_POST[ "signupsuccess"]=== true) ?>
    <?php echo "Logged in as: " . $_SESSION['user']['name'] . " (" . $_SESSION['user']['username'] . ")"; ?>
    <h2>To begin download our <a href="http://54.84.108.88/resources/extension.crx" download>extension</a> and begin the hunt!</h2>
    <h2>Check your <a href="/myprogress">stats</a>.</h2>
    <a href="logout.php">Logout</a>
    <?php } ?>

    <div id="bottom">By <a href="http://tombclarke.co.uk">Tom Clarke</a>, Cameron Angus and Rowan Cole</div>
</body>

</html>