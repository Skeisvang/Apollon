<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_apollon = "152.93.79.18";
$database_apollon = "artikler";
$username_apollon = "apollon";
$password_apollon = "1234";
$apollon = mysql_pconnect($hostname_apollon, $username_apollon, $password_apollon) or trigger_error(mysql_error(),E_USER_ERROR); 
?>