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

$qNavn = mysql_query("SELECT * FROM bruker WHERE feide = '" . $artikkel['bruker_feide'] ."'") or die(mysql_error());
$aNavn = mysql_fetch_assoc($qNavn);
$navn = $aNavn['fornavn'] . " " . $aNavn['etternavn'];

?>
<html>
	<head>
    	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body>
        <h2><?php echo $artikkel['overskrift']; ?></h2>
        <small>Skrevet av <?php echo $navn; ?></small>
        <p><?php echo $artikkel['artikkel']; ?></p>
        <a href="brukerartikler.php?feide=<?php echo $artikkel['bruker_feide']; ?>">Tilbake</a>
    </body>
</html>