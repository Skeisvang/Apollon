<?php
require_once("access_check.php");

mysql_select_db($database_apollon, $apollon);
if (!isset($_GET['id']))
	die("Du må spesifisere en artikkel!");
$id = $_GET['id'];
$q = "SELECT * FROM artikkel WHERE id = {$id}";
$r = mysql_query($q);
if (mysql_num_rows($r) == 0)
	die("Denne artikkelen finnes ikke");
$artikkel = mysql_fetch_assoc($r);

$qNavn = mysql_query("SELECT * FROM bruker WHERE feide = '" . $artikkel['bruker_feide'] ."'") or die(mysql_error());
$aNavn = mysql_fetch_assoc($qNavn);
$navn = $aNavn['fornavn'] . " " . $aNavn['etternavn'];
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
        <?php include 'menu.php' ?>
		
        <div id="page_content">
            <h2><?php echo $artikkel['overskrift']; ?></h2>
            <small>Skrevet av <?php echo $navn; ?></small>
            <p><?php echo $artikkel['artikkel']; ?></p>
            <a href="brukerartikler.php?feide=<?php echo $artikkel['bruker_feide']; ?>">Tilbake</a>
        </div>
        
        <p></p>
    </body>
</html>
