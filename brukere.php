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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="page.css" type="text/css" />
<title>Brukerliste</title>
</head>

<body>
<table border="1">
  <tr>
    <td>feide</td>
    <td>passord</td>
    <td>fornavn</td>
    <td>etternavn</td>
    <td>mail</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_brukere['feide']; ?></td>
      <td><?php echo $row_brukere['passord']; ?></td>
      <td><?php echo $row_brukere['fornavn']; ?></td>
      <td><?php echo $row_brukere['etternavn']; ?></td>
      <td><?php echo $row_brukere['mail']; ?></td>
    </tr>
    <?php } while ($row_brukere = mysql_fetch_assoc($brukere)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($brukere);
?>
