<?php
require_once("Connections/apollon.php");
mysql_select_db($database_apollon, $apollon);
if (!isset($_GET['id']))
	die("Du må spesifisere en artikkel!");
$id = $_GET['id'];
$q = "SELECT * FROM artikkel WHERE id = {$id}";
$r = mysql_query($q);
if (mysql_num_rows($r) == 0)
	die("Denne artikkelen finnes ikke");

$artikkel = mysql_fetch_assoc($r);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <meta http-equiv="Content-Type" content="text/html"; charset="UTF-8" />
        <link rel="stylesheet" href="page.css" type="text/css" />
        <link rel="stylesheet" href="menu.css" type="text/css" />
    </head>
    <body>
        <div id="page_header">
        	<h1>Apollon</h1>
        </div>        
        <ul id="menu">
            <li><a href="index.php">Hjem</a></li>
            <li><a href="#">Artikler</a></li>
            <li><a href="#">Brukeroversikt</a></li>
        </ul>
        <div id="page_content">
            <h2><?php echo $artikkel['overskrift']; ?></h2>
            <small>Skrevet av <?php echo $artikkel['bruker_feide']; ?></small>
            <p><?php echo nl2br($artikkel['artikkel']); ?></p>
        </div>
    </body>
</html>