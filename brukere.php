<?php 
require_once("access_check.php");

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_brukere = 30;
$pageNum_brukere = 0;
if (isset($_GET['pageNum_brukere'])) {
  $pageNum_brukere = $_GET['pageNum_brukere'];
}
$startRow_brukere = $pageNum_brukere * $maxRows_brukere;

mysql_select_db($database_apollon, $apollon);
$query_brukere = "SELECT b.*, count(a.id) as antall FROM bruker b left join artikkel a on (b.feide = a.bruker_feide) group by b.feide ";
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
		
<?php include 'menu.php' ?>
	
		<div id="page_content">
			<p></p>
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
			    <th>antall-art</th>
			    <th>mail</th>
			  </tr>
			  <tbody>
			  <?php do { ?>
			    <tr>
			      <td><a href="brukerartikler.php?feide=<?php echo $row_brukere['feide']; ?>"><?php echo $row_brukere['feide']; ?></a></td>
			      <td><?php  if ($isadmin) {  echo $row_brukere['passord']; } else { echo 'xxxx'; } ?></td>
			      <td><?php echo $row_brukere['fornavn']; ?></td>
			      <td><?php echo $row_brukere['etternavn']; ?></td>
			      <td><?php echo $row_brukere['antall']; ?></td>
			      <td><?php echo $row_brukere['mail']; ?></td>
			    </tr>
      <?php } while ($row_brukere = mysql_fetch_assoc($brukere)); ?>
                          </tbody>
			</table>
            <p></p>
		</div>
</body>
</html>
<?php
mysql_free_result($brukere);
?>
