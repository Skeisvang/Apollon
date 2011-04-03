<?php
require_once("access_check.php");

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
           $escore[$art["artikkel_bruker_feide"]] = new stdClass;
           $escore[$art["artikkel_bruker_feide"]]->poeng = 0;
           $escore[$art["artikkel_bruker_feide"]]->antall = 0;
        }
        $escore[$art["artikkel_bruker_feide"]]->poeng += $art["karakter"];
        $escore[$art["artikkel_bruker_feide"]]->antall ++;
    }
}
foreach ($escore as $feide => $s) {
    $s->base = round($s->poeng/$s->antall);
}

print '</pre>';
print '<table class="scores">';
print '<caption>karakterliste</caption>';
print '<tr><th>Elev</th><th>Antall vurderinger</th><th>Snitt</th></tr>';
foreach ($escore as $feide => $score) {
    print "<tr><td>{$feide}</td> <td>{$score->antall}</td> <td>" 
        . round($score->poeng/$score->antall,2) ."</td></tr>";
}
print '</table>';

?>
