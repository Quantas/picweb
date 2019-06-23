<?php require_once('config.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE `user` SET username=%s, first_name=%s, last_name=%s, join_date=%s, `role`=%s, email=%s WHERE `uid`=%s",
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['first_name'], "text"),
                       GetSQLValueString($_POST['last_name'], "text"),
                       GetSQLValueString($_POST['join_date'], "date"),
                       GetSQLValueString($_POST['role'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['uid'], "int"));

  mysql_select_db($database_picweb, $picweb);
  $Result1 = mysql_query($updateSQL, $picweb) or die(mysql_error());

  $updateGoTo = "admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_picweb, $picweb);
$query_edituser = "SELECT * FROM `user` WHERE uid = '".$_GET['uid']."'";
$edituser = mysql_query($query_edituser, $picweb) or die(mysql_error());
$row_edituser = mysql_fetch_assoc($edituser);
$totalRows_edituser = mysql_num_rows($edituser);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PicWeb - Edit User</title>
</head>

<body>
<?php require_once('header.php'); 
if ($_SESSION['role'] =='admin'){ ?>

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Uid:</td>
      <td><?php echo $row_edituser['uid']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Username:</td>
      <td><input type="text" name="username" value="<?php echo htmlentities($row_edituser['username'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">First_name:</td>
      <td><input type="text" name="first_name" value="<?php echo htmlentities($row_edituser['first_name'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Last_name:</td>
      <td><input type="text" name="last_name" value="<?php echo htmlentities($row_edituser['last_name'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Join_date:</td>
      <td><input type="text" name="join_date" value="<?php echo htmlentities($row_edituser['join_date'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Role:</td>
      <td><select name="role">
        <option value="user" <?php if (!(strcmp("user", htmlentities($row_edituser['role'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>user</option>
        <option value="admin" <?php if (!(strcmp("admin", htmlentities($row_edituser['role'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>admin</option>
      </select>
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Email:</td>
      <td><input type="text" name="email" value="<?php echo htmlentities($row_edituser['email'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update record" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="uid" value="<?php echo $row_edituser['uid']; ?>" />
</form>
<?php }else{
echo "Access Denied";
}
require_once('footer.php'); ?>
</body>
</html>
<?php
mysql_free_result($edituser);
?>
