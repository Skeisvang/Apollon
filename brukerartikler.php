<?php
require_once('Connections/apollon.php');
mysql_select_db($database_apollon, $apollon);
if (!isset($_GET['feide']))
	die("Du må spesifisere feide-iden");
$feide = $_GET['feide'];
$q = "SELECT * FROM artikkel WHERE bruker_feide = '{$feide}'";
$r = mysql_query($q, $apollon) or die(mysql_error());
$artikkel = mysql_fetch_assoc($r);

$qNavn = mysql_query("SELECT * FROM bruker WHERE feide = '{$feide}'") or die(mysql_error());
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
                
<div id="page_header">
	<h1 id="logo">Apollon</h1>
</div>

<ul id="menu">
	<li><a href="index.php">Hjem</a></li>
	<li><a href="lastoppart.php">Ny Artikkel</a></li>
	<li><a href="brukere.php">Brukeroversikt</a></li>
</ul>       
 <div id="page_content">
            <table class="brukerliste">
                <h2><?php echo $navn; ?> sine artikler</h2>
                <tr>
                    <th>Overskrift</th>
                    <th>Publisert</th>    
                </tr>
                <?php do { ?>
                <tr>
                    <td><a href="artikkel.php?id=<?php echo $artikkel['id'];?>"><?php echo $artikkel['overskrift']; ?></a></td>
                    <td><?php echo $artikkel['publisert']; ?></td>
                </tr>
                <?php } while ($artikkel = mysql_fetch_assoc($r)); ?>
            </table>
        </div>
    </body>
</html>
       