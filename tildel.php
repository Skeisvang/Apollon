<?php
require_once('Connections/apollon.php');
mysql_select_db($database_apollon, $apollon);
 
$sql = "SELECT a.id,a.overskrift,a.bruker_feide FROM artikkel a ";
$rc = mysql_query($sql, $apollon) or die(mysql_error());
$artlist = array();
while($artlist[] = mysql_fetch_assoc($rc));
array_pop($artlist);

// finn alle publiserte artikler
$sql = "SELECT a.id,a.overskrift,a.bruker_feide,count(k.id) as ant FROM artikkel a left join karakter k on (a.id = k.artikkel_id) where a.publisert is not null group by a.id";
$rc = mysql_query($sql, $apollon) or die(mysql_error());
$published = array();
while($published[] = mysql_fetch_assoc($rc));
array_pop($published);

// finn alle vurderte artikler
$sql = "SELECT k.artikkel_id, count(id) as ant FROM karakter k group by k.artikkel_id ";
$rc = mysql_query($sql, $apollon) or die(mysql_error());
$vurderinger = array();
while($vurderinger[] = mysql_fetch_assoc($rc));
array_pop($vurderinger);


$count = 0;
if (count($vurderinger)) {
  foreach ($vurderinger as $v) {
	  $count += $v["ant"];
  }
}

$mine = 0;
if (count($published)) {
  foreach ($published as $v) {
	  if ($v["bruker_feide"] == $user) {
	    $mine += $v["ant"];
	  }
  }
}

/*
print '<pre>';
print_r($artlist);
print '</pre>';
*/
?>