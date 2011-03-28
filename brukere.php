<?php require_once('Connections/apollon.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "user";
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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_brukere = 10;
$pageNum_brukere = 0;
if (isset($_GET['pageNum_brukere'])) {
  $pageNum_brukere = $_GET['pageNum_brukere'];
}
$startRow_brukere = $pageNum_brukere * $maxRows_brukere;

mysql_select_db($database_apollon, $apollon);
$query_brukere = "SELECT * FROM bruker";
$query_limit_brukere = sprintf("%s LIMIT %d, %d", $query_brukere, $startRow_brukere, $maxRows_brukere);
$brukere = mysql_query($query_limit_brukere, $apollon) or die(mysql_error());
$row_brukere = mysql_fetch_assoc($brukere);

if (isset($_GET['totalRows_brukere'])) {
  $totalRows_brukere = $_GET['totalRows_brukere'];
} else {
  $all_brukere = mysql_query($query_brukere);
  $totalRows_brukere = mysql_num_rows($all_brukere);
}
$totalPages_brukere = ceil($totalRows_brukere/$maxRows_brukere)-1;

$queryString_brukere = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_brukere") == false && 
        stristr($param, "totalRows_brukere") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_brukere = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_brukere = sprintf("&totalRows_brukere=%d%s", $totalRows_brukere, $queryString_brukere);
$user = '';
$isadmin = false;
if (isset($_SESSION['MM_Username'] )) {
	$user = $_SESSION['MM_Username'];	
	$isadmin = in_array($user,explode(',',"msjursen,au,ninamctiernan,meikeland,kfludal,sjoenh"));
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
		<div id="page_header">
			<h1>Apollon</h1>
		</div>
		
		<ul id="menu">
			<li><a href="index.php">Hjem</a></li>
			<li><a href="#">Artikler</a></li>
			<li><a href="brukere.php">Brukeroversikt</a></li>
		</ul>
		
		<div id="page_content">
			<table class="brukerliste">
			  <caption >
				<table class="navbuttons">
				  <tr>
				    <td><?php if ($pageNum_brukere > 0) { // Show if not first page ?>
					<a href="<?php printf("%s?pageNum_brukere=%d%s", $currentPage, 0, $queryString_brukere); ?>"><img src="images/First.gif" /></a>
					<?php } // Show if not first page ?></td>
				    <td><?php if ($pageNum_brukere > 0) { // Show if not first page ?>
					<a href="<?php printf("%s?pageNum_brukere=%d%s", $currentPage, max(0, $pageNum_brukere - 1), 
					    $queryString_brukere); ?>"><img src="images/Previous.gif" /></a>
					<?php } // Show if not first page ?></td>
				    <td><?php if ($pageNum_brukere < $totalPages_brukere) { // Show if not last page ?>
					<a href="<?php printf("%s?pageNum_brukere=%d%s", $currentPage, min($totalPages_brukere, $pageNum_brukere + 1), 
					    $queryString_brukere); ?>"><img src="images/Next.gif" /></a>
					<?php } // Show if not last page ?></td>
				    <td><?php if ($pageNum_brukere < $totalPages_brukere) { // Show if not last page ?>
					<a href="<?php printf("%s?pageNum_brukere=%d%s", $currentPage, $totalPages_brukere, 
                                                  $queryString_brukere); ?>"> <img src="images/Last.gif" /></a>
					<?php } // Show if not last page ?></td>
				  </tr>
				</table>
                          </caption>
			  <tr>
			    <th>feide</th>
			    <th>passord</th>
			    <th>fornavn</th>
			    <th>etternavn</th>
			    <th>mail</th>
			  </tr>
			  <tbody>
			  <?php do { ?>
			    <tr>
			      <td><?php echo $row_brukere['feide']; ?></td>
			      <td><?php  if ($isadmin) {  echo $row_brukere['passord']; } else { echo 'xxxx'; } ?></td>
			      <td><?php echo $row_brukere['fornavn']; ?></td>
			      <td><?php echo $row_brukere['etternavn']; ?></td>
			      <td><?php echo $row_brukere['mail']; ?></td>
			    </tr>
      <?php } while ($row_brukere = mysql_fetch_assoc($brukere)); ?>
                          </tbody>
			</table>
           
		</div>
</body>
</html>
<?php
mysql_free_result($brukere);
?>
