<?php require_once('config.php');
if ((isset($_GET['id'])) && ($_GET['id'] != "") && ($_SESSION['role'] == "admin")) {
  $deleteSQL = sprintf("DELETE FROM news WHERE id=%s",
                       GetSQLValueString($_GET['id'], "int"));

  mysql_select_db($database_picweb, $picweb);
  $Result1 = mysql_query($deleteSQL, $picweb) or die(mysql_error());

  $deleteGoTo = "admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
?>