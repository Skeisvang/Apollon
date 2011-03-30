<?php
require_once("Connections/apollon.php");
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
//   For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

//   When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
//   Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
//     Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
//     Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
//     Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}

$user = '';
$isadmin = false;
if (isset($_SESSION['MM_Username'] )) {
	mysql_select_db($database_apollon, $apollon);
	$user = $_SESSION['MM_Username'];	
	$isadmin = in_array($user,explode(',',"msjursen,au,ninamctiernan,meikeland,kfludal,sjoenh"));
<<<<<<< HEAD
	
	
/*$query_Recordset1 = sprintf("SELECT artikkel.id, artikkel.overskrift, artikkel.artikkel, artikkel.publisert, artikkel.bruker_feide, artikkel.lerarkarakter FROM artikkel where artikkel.bruker_feide='%s'", $user);
=======
//	 kode for å vise tildelte artikler
//	"select count(id) from artikkel where bruker_feide = '%s' "
//	"select * from karakter k where bruker_feide='%s' ";
	/*
	mysql_select_db($database_apollon, $apollon);
$query_Recordset1 = sprintf("SELECT artikkel.id, artikkel.overskrift, artikkel.artikkel, artikkel.publisert, artikkel.bruker_feide, artikkel.lerarkarakter FROM artikkel where artikkel.bruker_feide='%s'", $user);
>>>>>>> 095abf47a25ec2847b64465a9bc7cf71c1e7a09a
$Recordset1 = mysql_query($query_Recordset1, $apollon) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
$artlist = array();
while($artlist[] = mysql_fetch_assoc($Recordset1));
array_pop($artlist);*/
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
<?php include 'menu.php' ?>
		<div id="page_content">
			<h1>Vurderinger</h1>
<?php
if (isset($_SESSION['MM_Username'] )) {
	$qNumArtikler = sprintf("select * from artikkel where bruker_feide = '%s'", $user);
	$rNumArtikler = mysql_query($qNumArtikler, $apollon);
	if (mysql_num_rows($rNumArtikler) > 0)
	{ // Brukeren har artikler og må vurdere
		$NumArtikler = mysql_num_rows($rNumArtikler);
		$qNumVurderinger = sprintf("select * from karakter where bruker_feide='%s'", $user);
		$rNumVurderinger = mysql_query($qNumVurderinger);
		if (mysql_num_rows($rNumVurderinger) > 0)
		{ // Brukeren har fått tildelt vurderinger
		
			$aVurderte = array();
			$aUvurderte = array();
			while ($aVurdering = mysql_fetch_assoc($rNumVurderinger))
			{
				if ($aVurdering['karakter'] == NULL)
				{
					$aVurderte[] = $aVurdering;
				}
				else
				{
					$aUvurderte[] = $aVurdering;
				}
			}
?>
            <p>Velkommen til Apollon, her er en status over dine vurderinger:</p>
            <table class="brukerliste">
            	<h2>Vurderte</h2>
            	<tr>
                	<th>Artikkel</th>
                    <th>Bruker</th>
                    <th>Karakter</th>
                </tr>
                <?php
                foreach ($aUvurderte as $a)
				{
					print '<tr>';
					print '<td><a href="vurder.php?id='.$a['artikkel_id'].'>'.$a['overskrift'].'</a></td>';
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
					print '<td><a href="vurder.php?id='.$a['artikkel_id'].'>'.$a['overskrift'].'</a></td>';
					print '<td>'.$a['artikkel_bruker_feide'].'</td>';
					print '</tr>';
				}
				?>
            </table>
<?php
            }
		}
	}
?>
		</div>
	</body>
</html>
