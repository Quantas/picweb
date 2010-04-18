<?php require_once('config.php');
if ((isset($_GET['id'])) && ($_GET['id'] != "") && (isset($_SESSION['uid']))) {
  $deleteSQL = sprintf("DELETE FROM comment WHERE id=%s AND uid=%s",
                       GetSQLValueString($_GET['id'], "int"),
					   GetSQLValueString($_SESSION['uid'], "int"));

  mysql_select_db($database_picweb, $picweb);
  $Result1 = mysql_query($deleteSQL, $picweb) or die(mysql_error());

  $deleteGoTo = "userget.php?albumid=".$_GET['albumid']."&pic=".$_GET['pic'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
?>