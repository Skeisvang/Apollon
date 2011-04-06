<?php
require_once("access_check.php");

// finn alle elever
$sql = "select * from bruker order by etternavn, fornavn";
$rc = mysql_query($sql, $apollon) or die(mysql_error());
$elever = array();
while($elever[] = mysql_fetch_assoc($rc));
array_pop($elever);

$sql = "select * from karakter order by artikkel_id";
$rc = mysql_query($sql, $apollon) or die(mysql_error());
$artlist = array();
while($artlist[] = mysql_fetch_assoc($rc));
array_pop($artlist);

print '<pre>';
$escore = array();
foreach ($artlist as $art) {
    if (isset($art["karakter"]) ) {
        if(!isset($escore[$art["artikkel_bruker_feide"]])) {
           $feide = strtolower($art["artikkel_bruker_feide"]);
           $escore[$feide] = new stdClass;
           $escore[$feide]->poeng = 0;
           $escore[$feide]->antall = 0;
           $escore[$feide]->vurd = 0;
        }
        if(!isset($escore[strtolower($art["bruker_feide"])])) {
           $feide = strtolower($art["bruker_feide"]);
           $escore[$feide] = new stdClass;
           $escore[$feide]->poeng = 0;
           $escore[$feide]->antall = 0;
           $escore[$feide]->vurd = 0;
        }
        $escore[strtolower($art["bruker_feide"])]->vurd ++;
        $escore[strtolower($art["artikkel_bruker_feide"])]->poeng += $art["karakter"];
        $escore[strtolower($art["artikkel_bruker_feide"])]->antall ++;
    }
}
foreach ($escore as $feide => $s) {
    //$s->base = round($s->poeng/$s->antall);
}

print '</pre>';
print '<table class="sammendrag">';
print '<caption>karakterliste</caption>';
print '<tr><th>Nr</th><th>Etternavn</th><th>Fornavn</th><th>feide</th><th>Antall vurderinger</th><th>Snitt</th><th>Antall artikler vurdert</th></tr>';
$i = 1;
foreach ($elever as $elev) {
    //foreach ($escore as $feide => $score) {
    $feide = strtolower($elev["feide"]);
    $fn = $elev["fornavn"];
    $ln = $elev["etternavn"];
    if (isset($escore[$feide])) {
      $score = $escore[$feide]; 
      $kar =  ($score->antall > 0) ? round($score->poeng / $score->antall,2) : 0;
      print "<tr><td>$i</td><td>$ln</td><td>$fn</td><td>{$feide}</td> <td class='scores'>{$score->antall}</td> <td class='scores'>" 
          . round($kar,2) ."</td><td class='scores'>{$score->vurd}</td></tr>";
    } else {
      print "<tr><td>$i</td><td>$ln</td><td>$fn</td><td>{$feide}</td> <td></td> <td>" 
          . "</td><td></td></tr>";
    }
    $i++;
}
print '</table>';

?>
