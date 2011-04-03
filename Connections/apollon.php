<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_apollon = "localhost";
$database_apollon = "artikkel";
$username_apollon = "admin";
$password_apollon = "123";
$apollon = mysql_pconnect($hostname_apollon, $username_apollon, $password_apollon) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
