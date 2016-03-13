<?php 
    session_start();
    if(isset($_SESSION['user']) == 1) {
    //    header("Location: /index.php");
    }
    include( "../resources/web/sql.php"); 
    include("../resources/web/meta.php");
?>

<html>
    <head>
        <title>Egg Hack - My Progress</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js" charset="utf-8"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="myprogress.js"></script>
        <link type="text/css" rel="stylesheet" href="myprogress.css" />
    </head>
    <body onload="makeTree("<?php echo getGottenEggs($_SESSION['user']['userid']); ?>");">
        <div id="logo">
            <img src="/resources/EggHack.png"></img>
        </div>
        <div id="tree">
        </div>
    </body>
</html>