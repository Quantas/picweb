<?php
require_once('config.php');
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$publicBool = "false";

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

$myFilter = new InputFilter();
$_POST = $myFilter->process($_POST);
$name = htmlentities($_POST['name']);

  $insertSQL = sprintf("INSERT INTO user_album (`uid`, name, `public`) VALUES (%s, %s, %s)",
                       GetSQLValueString($_SESSION['uid'], "text"),
                       GetSQLValueString($name, "text"),
                       GetSQLValueString($publicBool, "text"));

  mysql_select_db($database_picweb, $picweb);
  $Result1 = mysql_query($insertSQL, $picweb) or die(mysql_error());

  $insertGoTo = "manage.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_albums = "-1";
if (isset($_SESSION['uid'])) {
  $colname_albums = $_SESSION['uid'];
}
mysql_select_db($database_picweb, $picweb);
$query_albums = sprintf("SELECT * FROM user_album WHERE `uid` = %s ORDER BY name ASC", GetSQLValueString($colname_albums, "int"));
$albums = mysql_query($query_albums, $picweb) or die(mysql_error());
$row_albums = mysql_fetch_assoc($albums);
$totalRows_albums = mysql_num_rows($albums);

$maxRows_user = 1;
$pageNum_user = 0;
if (isset($_GET['pageNum_user'])) {
  $pageNum_user = $_GET['pageNum_user'];
}
$startRow_user = $pageNum_user * $maxRows_user;

$colname_user = "-1";
if (isset($_SESSION['uid'])) {
  $colname_user = $_SESSION['uid'];
}
mysql_select_db($database_picweb, $picweb);
$query_user = sprintf("SELECT first_name, last_name, join_date, `role`, email FROM `user` WHERE `uid` = %s", GetSQLValueString($colname_user, "int"));
$query_limit_user = sprintf("%s LIMIT %d, %d", $query_user, $startRow_user, $maxRows_user);
$user = mysql_query($query_limit_user, $picweb) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);

if (isset($_GET['totalRows_user'])) {
  $totalRows_user = $_GET['totalRows_user'];
} else {
  $all_user = mysql_query($query_user);
  $totalRows_user = mysql_num_rows($all_user);
}
$totalPages_user = ceil($totalRows_user/$maxRows_user)-1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PicWeb - User Managment</title>
</head>

<body>
<?php require_once('header.php'); ?>
<center>
<table width="100%">
<td width="40%" valign="top" align="center">
<strong>User Information</strong><br />

<table class="bodyTable" cellspacing="0" cellpadding="4">
  <?php do { ?>
  <tr>
    <td class="adminSide"><strong>First Name</strong></td>
    <td class="tableTD"><?php echo $row_user['first_name']; ?></td>
  </tr>
  <tr>
    <td class="adminSide"><strong>Last Name</strong></td>
    <td class="tableTD"><?php echo $row_user['last_name']; ?></td>
  </tr>
  <tr>
    <td class="adminSide"><strong>Join Date</strong></td>
    <td class="tableTD"><?php echo $row_user['join_date']; ?></td>
  </tr>
  <tr>
    <td class="adminSide"><strong>Role</strong></td>
    <td class="tableTD"><?php echo $row_user['role']; ?></td>
  </tr>
  <tr>
    <td class="adminSide"><strong>E-mail</strong></td>
    <td class="tableTD"><?php echo $row_user['email']; ?></td>
  </tr>
  <tr>
    <td class="adminSide"><strong>Image Count</strong></td>
    <td class="tableTD"><?php checkUserImageCount($_SESSION['uid']); ?></td>
  </tr>
  <tr>
    <td class="adminSide"><strong>Space Used</strong></td>
    <td class="tableTD"><?php checkUserImageSize($_SESSION['uid']); ?></td>
  </tr>
    <tr>
    <td class="adminSide"><strong>Current Browser</strong></td>
    <td class="tableTD">
		<script type="text/javascript">
        var browser=navigator.appName;
        var b_version=navigator.appVersion;
        var version=parseFloat(b_version);
        document.write(browser+" "+version);
        </script>
    </td>
  </tr>
    <?php } while ($row_user = mysql_fetch_assoc($user)); ?>
</table>
</td>
<td width="60%" valign="top" align="center">
<strong>Albums</strong>
<?php if (($totalRows_albums) > '0') {?>
<table class="bodyTable" cellspacing="0" cellpadding="4">
  <tr>
    <th>Name</th>
    <th>Public</th>
    <th>Edit</th>
    <th>Delete</th>
  </tr>
  <?php do { ?>
    <tr>
      <td class="tableTD">
           <a href="gallery.php?albumid=<?php echo $row_albums['id']; ?>"><?php echo $row_albums['name']; ?></a>
      </td>
      <td class="tableTD"><?php echo $row_albums['public']; ?></td>
      <td class="tableTD"><a href="editalbum.php?albumid=<?php echo $row_albums['id']; ?>"><img src="images/icon_edit.png" border="0"/></a></td>
      <td class="tableTD"><a href="delete.php?id=<?php echo $row_albums['id']; ?>"><img src="images/icon_remove.png" border="0" /></a></td>
    </tr>
    <?php } while ($row_albums = mysql_fetch_assoc($albums)); ?>
</table>
<?php }else{ 
	echo "<br />You have no albums<br />";}?><br />

<strong>New Album</strong><br />
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <label>
  <input type="text" name="name" id="name" />
  </label>
  <label>
  <input type="submit" name="Create" id="Create" value="Create" />
  </label>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
</td>
</table>

</center>
<?php require_once('footer.php'); ?>
</body>
</html>
<?php
mysql_free_result($albums);

mysql_free_result($user);

mysql_free_result($comments);
?>
