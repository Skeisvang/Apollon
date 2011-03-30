<?php
require_once('Connections/apollon.php');
mysql_select_db($database_apollon, $apollon);
if (!isset($_GET['feide']))
	die("Du må spesifisere feide-iden");
$feide = $_GET['feide'];
$q = "SELECT * FROM artikkel WHERE bruker_feide = '{$feide}'";
$r = mysql_query($q, $apollon) or die(mysql_error());
$artikkel = mysql_fetch_assoc($r);
?>
<table border="1">
  <h2><?php echo $feide; ?> sine artikler</h2>
  <tr>
    <th>Overskrift</th>
    <th>Artikkel</th>
    <th>Publisert</th>    
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="artikkel.php?id=<?php echo $artikkel['id'];?>"><?php echo $artikkel['overskrift']; ?></a></td>
      <td><?php echo $artikkel['artikkel']; ?></td>
      <td><?php echo $artikkel['publisert']; ?></td>
    </tr>
    <?php } while ($artikkel = mysql_fetch_assoc($r)); ?>
</table>
