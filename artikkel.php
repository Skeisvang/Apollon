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
<h2><?php echo $artikkel['overskrift']; ?></h2>
<small>Skrevet av <?php echo $artikkel['bruker_feide']; ?></small>
<p><?php echo nl2br($artikkel['artikkel']); ?></p>