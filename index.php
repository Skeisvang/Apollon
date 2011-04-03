<?php
require_once("access_check.php");

if (isset($_SESSION['MM_Username'] )) {
    // tildeling av artikler
    include("tildel.php");
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
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
        <h1>Velkommen <?php echo $user?></h1>
               <div class="testcode">
            <?php
            print count($artlist). " artikler er lagret i systemet<br>";
            print count($published). " artikler er publisert<br>";
            print count($vurderinger). " artikler er tildelt til vurdering<br>";
            print $vcount. " elever er tildelt artikler til vurdering<br>";
            
            print "<p>Du er tildelt {$mine} artikler til vurdering</p>"; 
            ?>
                </div>
<?php
if (isset($_SESSION['MM_Username'] )) {
    $qNumArtikler = sprintf("select * from artikkel where bruker_feide = '%s'", $user);
    $rNumArtikler = mysql_query($qNumArtikler, $apollon);
    if (mysql_num_rows($rNumArtikler) > 0)
    { // Brukeren har artikler og må vurdere
        $NumArtikler = mysql_num_rows($rNumArtikler);
        $qNumVurderinger = sprintf("select k.*,a.overskrift from karakter k inner join artikkel a 
               on (a.id = k.artikkel_id) where k.bruker_feide='%s'", $user);
        //print $qNumVurderinger;
        $rNumVurderinger = mysql_query($qNumVurderinger);
        if (mysql_num_rows($rNumVurderinger) > 0)
        { // Brukeren har fått tildelt vurderinger
        
            $aVurderte = array();
            $aUvurderte = array();
            while ($aVurdering = mysql_fetch_assoc($rNumVurderinger))
            {
                if ($aVurdering['karakter'] == NULL)
                {
                    $aUvurderte[] = $aVurdering;
                }
                else
                {
                    $aVurderte[] = $aVurdering;
                }
            }
?>
            <h1>Vurderinger</h1>
            <p>Velkommen til Apollon, her er en status over dine vurderinger:</p>
            <table class="brukerliste">
                <h2>Vurderte</h2>
                <tr>
                    <th>Artikkel</th>
                    <th>Bruker</th>
                    <th>Karakter</th>
                </tr>
                <?php
                foreach ($aVurderte as $a)
                {
                    print '<tr>';
                    print '<td><a href="vurder.php?id='.$a['artikkel_id'].'">'.$a['overskrift'].'</a></td>';
                    print '<td>'.$a['artikkel_bruker_feide'].'</td>';
                    print '<td>'.$a['karakter'].'</td>';
                    print '</tr>';
                }
                ?>
            </table>
            <table class="brukerliste">
                <h2>Uvurderte</h2>
                <tr>
                    <th>Artikkel</th>
                    <th>Bruker</th>
                </tr>
                <?php
                foreach ($aUvurderte as $a)
                {
                    print '<tr>';
                    print '<td><a href="vurder.php?id='.$a['artikkel_id'].'">'.$a['overskrift'].'</a></td>';
                    print '<td>'.$a['artikkel_bruker_feide'].'</td>';
                    print '</tr>';
                }
                ?>
            </table>
<?php
        } else {
            print "<p>Ingen artikler er tildelt deg ennå";
        }
    } else {
        print "<h1>Du har ikke lasta opp en artikkel ennå</h1>";
        print "Klikk på linken <b>Ny artikkel</b> i menyen og last opp teksten din";
    }
    }
?>
        </div>
    </body>
</html>
