<html>
    <head>
        <title></title>
        <?php include ?>
        <script> var rawJSON = <?php getGottenEggs($_SESSION['username']); ?> </script>
        <script src = "myprogress.js"></script>
    </head>
    <body onload="makeTree();">
        <div id="tree">
        </div>
    </body>
</html>