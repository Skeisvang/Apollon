<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

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
?>
<?php require_once('Connections/apollon.php'); ?>
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

//print_r($_POST);

$artid = 0;
if (isset($_GET["artid"])) $artid = (int)$_GET["artid"];	
if (isset($_POST["artid"])) $artid = (int)$_POST["artid"];

$user = '';
$isadmin = false;
if (isset($_SESSION['MM_Username'] )) {
	$user = $_SESSION['MM_Username'];	
	$isadmin = in_array($user,explode(',',"msjursen,au,ninamctiernan,meikeland,kfludal,sjoenh"));
	//print "came here";
	
	
	if (isset($_POST["artikkel"])) {
		// brukeren ønsker å lagre en ny artikkel
		$text = GetSQLValueString($_POST['artikkel'], "text");
		$over = GetSQLValueString($_POST["overskrift"],"text");
		$sql = sprintf("update artikkel set overskrift=%s,artikkel=%s where id=%s and bruker_feide='%s'", $over,$text,$artid,$user);
		//print "$sql";
		mysql_select_db($database_apollon, $apollon);
		mysql_query($sql, $apollon) or die(mysql_error());
		
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
<title>Untitled Document</title>
</head>

<body>
<form action="rediger_en_artikkel.php" method="post" name="rediger">
<input value="<?php echo $row_Recordset1['overskrift']; ?>" name="overskrift" type="text" /><p>
<textarea name="artikkel" cols="70" rows="40"><?php echo $row_Recordset1['artikkel']; ?></textarea>
<input name="save" type="submit" value="save" />
<input name="artid" type="hidden" value="<?php echo $artid; ?>" />
</form>

</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
