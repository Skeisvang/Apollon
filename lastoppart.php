<?php require_once('Connections/apollon.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
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
//Sjekker om bruker er admin.
$user = '';
$isadmin = false;
if (isset($_SESSION['MM_Username'] )) {
	$user = $_SESSION['MM_Username'];	
	$isadmin = in_array($user,explode(',',"msjursen,au,ninamctiernan,meikeland,kfludal,sjoenh"));

	if (isset($_POST["pubart"])) {
		// brukeren ønsker å publisere en artikkel
		$artid = GetSQLValueString($_POST['pubart'], "int");
		$sql = sprintf("update artikkel set publisert=null where bruker_feide='%s'",$user);
		//print "$sql";
		mysql_select_db($database_apollon, $apollon);
		mysql_query($sql, $apollon) or die(mysql_error());
		$sql = sprintf("update artikkel set publisert=%s where id=%s and bruker_feide='%s'", time(),$artid,$user);
		mysql_query($sql, $apollon) or die(mysql_error());
		
    }

	if (isset($_POST["artid"])) {
		// brukeren ønsker å slette en artikkel
		$artid = GetSQLValueString($_POST['artid'], "int");
		$sql = sprintf("delete from artikkel where id=%s and bruker_feide='%s'", $artid,$user);
		//print "$sql";
		mysql_select_db($database_apollon, $apollon);
		mysql_query($sql, $apollon) or die(mysql_error());
		
    }
	
	if (isset($_POST["artext"])) {
		// brukeren ønsker å lagre en ny artikkel
		$text = GetSQLValueString($_POST['artext'], "text");
		$over = GetSQLValueString($_POST["overskrift"],"text");
		$sql = sprintf("insert into artikkel (overskrift,artikkel,bruker_feide) values (%s,%s,'%s')", $over,$text,$user);
		//print "$sql";
		mysql_select_db($database_apollon, $apollon);
		mysql_query($sql, $apollon) or die(mysql_error());
		
    }
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_apollon, $apollon);
$query_Recordset1 = sprintf("SELECT id, overskrift, artikkel, publisert, bruker_feide, lerarkarakter FROM artikkel where bruker_feide='%s'", $user);
$Recordset1 = mysql_query($query_Recordset1, $apollon) or die(mysql_error());
//$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
$artlist = array();
while($artlist[] = mysql_fetch_assoc($Recordset1));
array_pop($artlist);
//print_r($artlist);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Last opp artikkel</title>
<link href="page.css" rel="stylesheet" type="text/css" />
<link href="menu.css" rel="stylesheet" type="text/css" />
</head>


<body>


<?php include 'menu.php' ?>


                <div id="page_content">
               


                    <?php
					   $qNavn = mysql_query("SELECT * FROM bruker WHERE feide = '" . $_SESSION["MM_Username"] ."'") or die(mysql_error());
					   $aNavn = mysql_fetch_assoc($qNavn);
					   $navn = $aNavn['fornavn'] . " " . $aNavn['etternavn'];
                       print "<h4>Velkommen {$navn}</h4> ";
					   print "Du har {$totalRows_Recordset1} artikler<p>";
					   print '<table class="brukerliste"><tr><th>Vis artikkel</th><th>Rediger</th><th>Slett</th></tr>';
					   foreach ($artlist as $art) {
						   print '<tr>';
						   print '<td><a target="_nyside" href="vis_en_artikkel.php?artid='.$art["id"] . '">' . $art["overskrift"] . '</a></td>';
						   print '<td><a  target="_annaside" href="rediger_en_artikkel.php?artid='.$art["id"] . '">' . $art["overskrift"] . '</a></td>';
						   print '<td><form action="lastoppart.php" method="post" name="remove"><input name="artid" type="hidden" value="'.$art["id"]
						                .'"><input name="slett" type="submit" value="slett" /></form></td>';
						   if ($art["publisert"]) {
							  print "<td>Publisert</td>";
						   } else {
						     print '<td><form action="lastoppart.php" method="post" name="publish"><input name="pubart" type="hidden" value="'.$art["id"]
						                .'"><input name="publiser" type="submit" value="publiser" /></form></td>';	
						   }
						   print '</tr>';
					   }	
					   print '</table>';
                    ?>
                    <p>
                    <form action="lastoppart.php" method="post" name="nyart">
                      <p>Overskrift:
                        <input value="" name="overskrift" size="50" type="text" />
                      </p>
                      <p>
                        Artikkeltekst:<br />
                        <textarea name="artext" cols="70" rows="30"></textarea>
                      </p>
                      <input name="lagre" type="submit" value="Lagre" />
                    </form>
                    </p>
               </div>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
