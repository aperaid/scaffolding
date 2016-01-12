<?php

error_reporting(E_ALL ^ E_DEPRECATED);

# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_Connection = "181.224.157.45";
$database_Connection = "apera145_scaffolding";
$username_Connection = "apera145_bdn";
$password_Connection = "890iop890iop";
$Connection = mysql_pconnect($hostname_Connection, $username_Connection, $password_Connection) or trigger_error(mysql_error(),E_USER_ERROR); 
?>