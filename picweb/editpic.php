<?php require_once('config.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE image SET name=%s WHERE id=%s AND uid=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['id'], "int"),
					   GetSQLValueString($_SESSION['uid'], "int"));

  mysql_select_db($database_picweb, $picweb);
  $Result1 = mysql_query($updateSQL, $picweb) or die(mysql_error());

  $updateGoTo = "gallery.php?albumid=".$_GET['albumid'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_editpic = "-1";
if (isset($_GET['pic'])) {
  $colname_editpic = $_GET['pic'];
}
mysql_select_db($database_picweb, $picweb);
$query_editpic = sprintf("SELECT name, id FROM image WHERE id = %s and uid= %s", GetSQLValueString($colname_editpic, "int"), GetSQLValueString($_SESSION['uid'], "int"));
$editpic = mysql_query($query_editpic, $picweb) or die(mysql_error());
$row_editpic = mysql_fetch_assoc($editpic);
$totalRows_editpic = mysql_num_rows($editpic);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PicWeb - Edit Picture</title>
</head>

<body>
<?php require_once('header.php');
if (($totalRows_editpic) > '0') { ?>

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Name:</td>
      <td><input type="text" name="name" value="<?php echo htmlentities($row_editpic['name'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Image:</td>
      <td><img src="displaypic.php?pic=<?php echo $row_editpic['id'];?>" width="100"  /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update record" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id" value="<?php echo $row_editpic['id']; ?>" />
</form>
<center>
<a href="deletepic.php?albumid=<?php echo $_GET['albumid'] ?>&id=<?php echo $row_editpic['id'];?>">Delete Picture</a><br />
</center>
<?php } else { echo "Invalid Image, please press back"; } ?>
<?php require_once('footer.php'); ?>
</body>
</html>
<?php
mysql_free_result($editpic);
?>
