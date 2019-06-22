<?php
$time = microtime();
$time = explode(" ", $time);
$time = $time[1] + $time[0];
$start = $time;

# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
if (!isset($_SESSION)) {
  session_start();
}
$hostname_picweb = "database";
$database_picweb = "picweb";
$username_picweb = "picweb";
$password_picweb = "picweb";
$picweb = mysql_pconnect($hostname_picweb, $username_picweb, $password_picweb) or trigger_error(mysql_error(),E_USER_ERROR); 
$picwebi = new mysqli($hostname_picweb,$username_picweb,$password_picweb,$database_picweb);
//Global Vars
$logging = "true"; //set to false to disable page logging
//includes for all our scripts
require_once('scripts/inputFilter.php');
require_once('scripts/scripts.php');
require_once('scripts/sql.php');
require_once('scripts/gallery.php');
require_once('scripts/news.php');
require_once('scripts/comment.php');
?>
