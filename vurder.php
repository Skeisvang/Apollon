<?php
require_once("access_check.php");

if (!(isset($_GET['id']) or isset($_POST['id'])) )
    die("Du må spesifisere en artikkel!");
$id = $_GET['id'] or $_POST['id'];

// lagrer vurderingen
if (isset($_SESSION['MM_Username'] )) {

    if (isset($_POST["vurdering"])) {
        // brukeren ønsker vurdere en artikkel
        $vurdering = GetSQLValueString($_POST['vurdering'], "text");
        $karakter = GetSQLValueString($_POST["karakter"],"int");
        $sql = sprintf("update karakter set karakter=%s, vurdering=%s where artikkel_id=%s and bruker_feide='%s'",$karakter,$vurdering,$id,$user);
        //print "$sql";
        mysql_select_db($database_apollon, $apollon);
        mysql_query($sql, $apollon) or die(mysql_error());

        
    }

}

// hent ut karakter-vurdering for artikkelen
$q = "SELECT k.karakter,k.vurdering,a.*,b.fornavn, b.etternavn FROM artikkel a 
    inner join bruker b on (b.feide = a.bruker_feide) 
    inner join karakter k on (k.artikkel_id = a.id and k.bruker_feide = '$user')
    WHERE a.id = {$id}";
$r = mysql_query($q);
if (mysql_num_rows($r) == 0)
    die("Denne artikkelen finnes ikke");
$art = mysql_fetch_assoc($r);

$navn = $art['fornavn'] . " " . $art['etternavn'];

/*
print '<pre>';
print_r($art);
print '</pre>';
// */

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
            <h1><a href="#vu">Vurdering</a></h1>
        </div>
        <h2><?php echo $art['overskrift']; ?></h2>
        <small>Skrevet av <?php echo $navn; ?></small>
        <p><?php echo $art['artikkel']; ?></p>

        <a name="vu"><h3>Vurdering</h3></a>
        <form method="post" class="vurdering" name="vurd" action="">
             <p class="label">Karakter  : 
                  <select name="karakter">
                    <option value="2" <?php if ($art["karakter"] == "2") print 'selected="selected"' ?> >2</option>
                    <option value="3" <?php if ($art["karakter"] == "3") print 'selected="selected"' ?> >3</option>
                    <option value="4" <?php if ($art["karakter"] == "4") print 'selected="selected"' ?> >4</option>
                    <option value="5" <?php if ($art["karakter"] == "5") print 'selected="selected"' ?> >5</option>
                  </select>
             </p>
             <p class="label">Vurdering :<textarea name="vurdering" cols="80" rows="10"><?php print $art["vurdering"] ?></textarea></p>
             <button type="submit" name="vuu" >Lagre</button>
             <input type="hidden" name="id" value="<?php print $id ?>">
        </form>

 
        <a href="index.php">Tilbake</a>
    </body>
</html>
