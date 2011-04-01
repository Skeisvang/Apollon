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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO bruker (feide, passord, fornavn, etternavn, mail) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['feide'], "text"),
                       GetSQLValueString($_POST['passord'], "text"),
                       GetSQLValueString($_POST['fornavn'], "text"),
                       GetSQLValueString($_POST['etternavn'], "text"),
                       GetSQLValueString($_POST['mail'], "text"));

  mysql_select_db($database_apollon, $apollon);
  $Result1 = mysql_query($insertSQL, $apollon) or die(mysql_error());

  $insertGoTo = "reg_ok.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<meta http-equiv="Content-Type" content="text/html"; charset="UTF-8" />
	<link rel="stylesheet" href="page.css" type="text/css" />
	<link rel="stylesheet" href="menu.css" type="text/css" />
</head>

<body>
	
	
<?php include 'menu.php' ?>

	<div id="page_content">
		<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
		  <table align="center">
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Feide:</td>
		      <td><input type="text" name="feide" value="" size="32" /></td>
		    </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Passord:</td>
		      <td><input type="text" name="passord" value="" size="32" /></td>
		    </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Fornavn:</td>
		      <td><input type="text" name="fornavn" value="" size="32" /></td>
		    </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Etternavn:</td>
		      <td><input type="text" name="etternavn" value="" size="32" /></td>
		    </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">Mail:</td>
		      <td><input type="text" name="mail" value="" size="32" /></td>
		    </tr>
		    <tr valign="baseline">
		      <td nowrap="nowrap" align="right">&nbsp;</td>
		      <td><input type="submit" value="Registrer bruker" /></td>
		    </tr>
		  </table>
		  <input type="hidden" name="MM_insert" value="form1" />
		</form>
	</div>
</body>
</html>
