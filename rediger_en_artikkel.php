<?php
require_once("access_check.php");

$artid = 0;
$savemsg = '';  // viser at lagring ok
if (isset($_GET["artid"])) $artid = (int)$_GET["artid"];	
if (isset($_POST["artid"])) $artid = (int)$_POST["artid"];

if (isset($_SESSION['MM_Username'] )) {
	if (isset($_POST["artikkel"])) {
		// brukeren ønsker å lagre en ny artikkel
		$text = GetSQLValueString($_POST['artikkel'], "text");
		$over = GetSQLValueString($_POST["overskrift"],"text");
		$sql = sprintf("update artikkel set overskrift=%s,artikkel=%s where id=%s and bruker_feide='%s'", $over,$text,$artid,$user);
		//print "$sql";
		mysql_select_db($database_apollon, $apollon);
		mysql_query($sql, $apollon) or die(mysql_error());
                $savemsg = "<h3>Artikkelen er lagra</h3>";
		
    }
}


mysql_select_db($database_apollon, $apollon);
$query_Recordset1 = "SELECT * FROM artikkel where id=" . $artid;
$Recordset1 = mysql_query($query_Recordset1, $apollon) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Type" content="text/html"; charset="UTF-8" />
    <link rel="stylesheet" href="page.css" type="text/css" />
    <link rel="stylesheet" href="menu.css" type="text/css" />
<title>Rediger en artikkel</title>
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
            <?php echo $savemsg ?>
            <form action="rediger_en_artikkel.php" method="post" name="rediger">
            <label>Overskrift: <input value="<?php echo $row_Recordset1['overskrift']; ?>" name="overskrift" type="text" /></label><p>
            <textarea name="artikkel" cols="70" rows="40"><?php echo $row_Recordset1['artikkel']; ?></textarea>
            <input name="save" type="submit" value="save" />
            <input name="artid" type="hidden" value="<?php echo $artid; ?>" />
            </form>
        </div>

</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
