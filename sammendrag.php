<?php
require_once("access_check.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <meta http-equiv="Content-Type" content="text/html"; charset="UTF-8" />
    <link rel="stylesheet" href="page.css" type="text/css" />
    <link rel="stylesheet" href="menu.css" type="text/css" />
</head>
    <body>
        <div id="page_header">
            <h1 id="logo">Apollon</h1>
        </div>
        
        <ul id="menu">
            <li><a href="index.php">Hjem</a></li>
            <li><a href="lastoppart.php">Ny Artikkel</a></li>
            <li><a href="brukere.php">Brukeroversikt</a></li>
        </ul>
        <?php
            if (!$isadmin) {
                print "atsjo";
                return;
            } 
         ?>
        <div id="page_content">
           <h1>Sammendrag</h1>
           <?php include("sammen.php") ?>
        </div>
        
    </body>
</html>
