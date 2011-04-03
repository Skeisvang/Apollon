<?php
require_once('Connections/apollon.php');
mysql_select_db($database_apollon, $apollon);
 
// finn alle artikler
$sql = "SELECT a.id,a.overskrift,a.bruker_feide FROM artikkel a ";
$rc = mysql_query($sql, $apollon) or die(mysql_error());
$artlist = array();
while($artlist[] = mysql_fetch_assoc($rc));
array_pop($artlist);

// finn alle publiserte artikler (+ antall tildelinger for hver)
$sql = "SELECT a.id,a.overskrift,a.bruker_feide,count(k.id) as ant FROM artikkel a left join karakter k on (a.id = k.artikkel_id) 
	    where a.publisert is not null group by a.id  order by ant";
$rc = mysql_query($sql, $apollon) or die(mysql_error());
$published = array();
while($published[] = mysql_fetch_assoc($rc));
array_pop($published);

// finn alle artikler som er delt ut til vurdering
$sql = "SELECT k.* FROM karakter k";
$rc = mysql_query($sql, $apollon) or die(mysql_error());
$vurderinger = array();
while($vurderinger[] = mysql_fetch_assoc($rc));
array_pop($vurderinger);

// finn alle artikler som er vurdert
$sql = "SELECT k.artikkel_id, count(id) as ant FROM karakter k where k.karakter is not null group by k.artikkel_id ";
$rc = mysql_query($sql, $apollon) or die(mysql_error());
$karakterer = array();
while($karakterer[] = mysql_fetch_assoc($rc));
array_pop($karakterer);

$vcount = 0;        // antall elever som er tildelt artikler
$evurd = array() ;  // id for elever som skal vurdere (slik at de kan telles)
$mine = 0;          // artikler som jeg skal vurdere
if (count($vurderinger)) {
  foreach ($vurderinger as $v) {
    $evurd[$v["bruker_feide"] ] = 1;
    if ($v["bruker_feide"] == $user) {
      $mine++;
    }
  }
  $vcount = count($evurd);
}



// dersom denne eleven har $mine < 4 - da deler vi ut artikler
if ($mine < 5) {
  foreach($published as $p) {
    if ($p["bruker_feide"] != $user and $p["ant"] < 7) {
        // gi denne artikkelen til eleven
        $sql = sprintf("insert into karakter (artikkel_id,artikkel_bruker_feide,bruker_feide) values  (%s,'%s','%s')", $p['id'],$p['bruker_feide'],$user);
        //print "$sql";
        mysql_select_db($database_apollon, $apollon);
        mysql_query($sql, $apollon) or die(mysql_error());
        $mine++;
        $vcount++;
        if ($mine > 4) break;
    }
  }
  
}

/*
print '<pre>';
print_r($published);
print '</pre>';
//*/
?>
