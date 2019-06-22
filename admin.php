<?php require_once('config.php');

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

// read in the uptime (using exec)
$uptime = exec("cat /proc/uptime");
$uptime = split(" ",$uptime);
$uptimeSecs = $uptime[0];

// get the static uptime
$staticUptime = format_uptime($uptimeSecs);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO news (`uid`, story, `date`) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['uid'], "int"),
                       GetSQLValueString($_POST['story'], "text"),
                       GetSQLValueString($_POST['date'], "date"));

  mysql_select_db($database_picweb, $picweb);
  $Result1 = mysql_query($insertSQL, $picweb) or die(mysql_error());

  $insertGoTo = "admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$maxRows_users = 10;
$pageNum_users = 0;
if (isset($_GET['pageNum_users'])) {
  $pageNum_users = $_GET['pageNum_users'];
}
$startRow_users = $pageNum_users * $maxRows_users;

mysql_select_db($database_picweb, $picweb);
$query_users = "SELECT user.uid as uid, user.username as username, user.first_name as first_name, user.last_name as last_name, user.join_date as join_date, user.role as role, user.email as email FROM user GROUP BY user.uid ORDER BY user.uid ASC";
$query_limit_users = sprintf("%s LIMIT %d, %d", $query_users, $startRow_users, $maxRows_users);
$users = mysql_query($query_limit_users, $picweb) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);

if (isset($_GET['totalRows_users'])) {
  $totalRows_users = $_GET['totalRows_users'];
} else {
  $all_users = mysql_query($query_users);
  $totalRows_users = mysql_num_rows($all_users);
}
$totalPages_users = ceil($totalRows_users/$maxRows_users)-1;

mysql_select_db($database_picweb, $picweb);
$query_countUsers = "SELECT COUNT(uid) as userCount FROM user";
$countUsers = mysql_query($query_countUsers, $picweb) or die(mysql_error());
$row_countUsers = mysql_fetch_assoc($countUsers);
$totalRows_countUsers = mysql_num_rows($countUsers);

mysql_select_db($database_picweb, $picweb);

$query_countImage = "SELECT COUNT(id) as imageCount FROM image";
$countImage = mysql_query($query_countImage, $picweb) or die(mysql_error());
$row_countImage = mysql_fetch_assoc($countImage);
$totalRows_countImage = mysql_num_rows($countImage);

mysql_select_db($database_picweb, $picweb);
$query_countComment = "SELECT COUNT(id) as commentCount FROM comment";
$countComment = mysql_query($query_countComment, $picweb) or die(mysql_error());
$row_countComment = mysql_fetch_assoc($countComment);
$totalRows_countComment = mysql_num_rows($countComment);

mysql_select_db($database_picweb, $picweb);
$query_countAlbums = "SELECT COUNT(id) as albumCount FROM user_album";
$countAlbums = mysql_query($query_countAlbums, $picweb) or die(mysql_error());
$row_countAlbums = mysql_fetch_assoc($countAlbums);
$totalRows_countAlbums = mysql_num_rows($countAlbums);

mysql_select_db($database_picweb, $picweb);
$query_news = "SELECT news.id as id, news.story as story, news.date as date, user.first_name as first_name, user.last_name as last_name FROM news, user WHERE news.uid = user.uid ORDER BY id DESC";
$news = mysql_query($query_news, $picweb) or die(mysql_error());
$row_news = mysql_fetch_assoc($news);
$totalRows_news = mysql_num_rows($news);

mysql_select_db($database_picweb, $picweb);
$query_dbsizequery = "SHOW TABLE STATUS";
$dbsizequery = mysql_query($query_dbsizequery, $picweb) or die(mysql_error());
$row_dbsizequery = mysql_fetch_assoc($dbsizequery);
$dbsize = 0;
do {  
            $dbsize += $row_dbsizequery['Data_length'] + $row_dbsizequery['Index_length']; 
			} while( $row_dbsizequery = mysql_fetch_assoc($dbsizequery) );
			
$df = disk_free_space("/");

$currentPage = $_SERVER["PHP_SELF"];

$queryString_users = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_users") == false && 
        stristr($param, "totalRows_users") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_users = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_users = sprintf("&totalRows_users=%d%s", $totalRows_users, $queryString_users);

mysql_select_db($database_picweb, $picweb);
$query_topfive = "SELECT user.first_name, user.last_name, SUM(size) as size FROM user, image WHERE user.uid = image.uid GROUP BY user.username ORDER BY size DESC LIMIT 5";
$topfive = mysql_query($query_topfive, $picweb) or die(mysql_error());
$row_topfive = mysql_fetch_assoc($topfive);
$totalRows_topfive = mysql_num_rows($topfive);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PicWeb - Administration Page</title>
<script src="scripts/spry/SpryTabbedPanels.js" type="text/javascript"></script>
<script language="javascript">
<!--
var upSeconds=<?php echo $uptimeSecs; ?>;
function doUptime() {
var uptimeString = "";
var secs = parseInt(upSeconds % 60);
var mins = parseInt(upSeconds / 60 % 60);
var hours = parseInt(upSeconds / 3600 % 24);
var days = parseInt(upSeconds / 86400);
if (days > 0) {
  uptimeString += days;
  uptimeString += ((days == 1) ? " day" : " days");
}
if (hours > 0) {
  uptimeString += ((days > 0) ? ", " : "") + hours;
  uptimeString += ((hours == 1) ? " hour" : " hours");
}
if (mins > 0) {
  uptimeString += ((days > 0 || hours > 0) ? ", " : "") + mins;
  uptimeString += ((mins == 1) ? " minute" : " minutes");
}
if (secs > 0) {
  uptimeString += ((days > 0 || hours > 0 || mins > 0) ? ", " : "") + secs;
  uptimeString += ((secs == 1) ? " second" : " seconds");
}
var span_el = document.getElementById("uptime");
var replaceWith = document.createTextNode(uptimeString);
span_el.replaceChild(replaceWith, span_el.childNodes[0]);
upSeconds++;
setTimeout("doUptime()",1000);
}
// -->
</script>
<link href="scripts/spry/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php require_once('header.php'); ?>
<?php if($_SESSION['role'] == 'admin') { ?>
<div id="TabbedPanels1" class="TabbedPanels">
  <ul class="TabbedPanelsTabGroup">
    <li class="TabbedPanelsTab" tabindex="0">Server Info</li>
    <li class="TabbedPanelsTab" tabindex="0">User Info</li>
    <li class="TabbedPanelsTab" tabindex="0">News Admin.</li>
    <li class="TabbedPanelsTab" tabindex="0">Top 5 Users by Size</li>
  </ul>
  <div class="TabbedPanelsContentGroup">
    <div class="TabbedPanelsContent">
<table class="bodyTable" cellspacing="0" cellpadding="4" align="center">
	<tr>
    <th class="tableTD" colspan="2">
    Server Statistics
    </th>
    </tr>
    <tr><td class="adminSide"><strong>DB Size</strong></td>
    <td class="tableTD">
    <?php echo formatfilesize($dbsize); ?>
    </td>
    </tr>
        <tr>
    <td class="adminSide"><strong>Image Size</strong></td>
    <td class="tableTD">
      <?php checkAllImageSize(); ?></td>
    </tr>
    <tr>
    <td class="adminSide"><strong>User Count</strong></td>
    <td class="tableTD">
    <?php echo $row_countUsers['userCount']; ?></td>
    </tr>
        <tr>
    <td class="adminSide"><strong>Album Count</strong></td>
    <td class="tableTD">
      <?php echo $row_countAlbums['albumCount']; ?></td>
    </tr>
    <tr>
    <td class="adminSide"><strong>Image Count</strong></td>
    <td class="tableTD">
      <?php echo $row_countImage['imageCount']; ?></td>
    </tr>
         <tr>
    <td class="adminSide"><strong>Comment Count</strong></td>
    <td class="tableTD">
      <?php echo $row_countComment['commentCount']; ?></td>
    </tr>
    <?php if (checkLinux() =="True"){ ?>
    <tr>
    <td class="adminSide"><strong>Server Uptime</strong></td>
    <td class="tableTD">
    <div id="uptime"><?php echo $staticUptime; ?></div>
    </td>
    </tr>
    <tr>
    <td class="adminSide"><strong>Load Average</strong></td>
    <td class="tableTD">
    <?php echo getLoadAvg(); ?>
    </td>
    </tr>
    <tr>
    <td class="adminSide"><strong>Full Server URL</strong></td>
    <td class="tableTD">
    <?php echo fullServerName(); ?>
    </td>
    </tr>
    <tr>
    <td class="adminSide"><strong>Free Space on /</strong></td>
    <td class="tableTD">
    <div id="uptime"><?php echo formatfilesize($df); ?></div>
    </td>
    </tr>
    <?php } ?>
    <tr>
    <td class="adminSide"><strong>PHPInfo</strong></td>
    <td class="tableTD">
    <a href="scripts/info.php">Click Here</a>
    </td>
    </tr>
</table>
    </div>
    <div class="TabbedPanelsContent">
    <table class="bodyTable" cellspacing="0" cellpadding="4">
  <tr>
  	<th colspan="12">User Information</th>
  </tr>
  <tr>
    <th>User ID</th>
    <th>Username</th>
    <th>Albums</th>
    <th>Images</th>
    <th>Space Used</th>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Join Date</th>
    <th>Role</th>
    <th>E-mail</th>
    <th>Edit</th>
    <th>Delete</th>
  </tr>
  <?php do { ?>
  <?php $uid = $row_users['uid'];?>
    <tr>
      <td class="tableTD"><?php echo $row_users['uid']; ?></td>
      <td class="tableTD"><?php echo $row_users['username']; ?></td>
      <td class="tableTD"><?php checkUserAlbumCount($uid); ?></td>
      <td class="tableTD"><?php checkUserImageCount($uid); ?></td>
      <td class="tableTD"><?php checkUserImageSize($uid); ?></td>
      <td class="tableTD"><?php echo $row_users['first_name']; ?></td>
      <td class="tableTD"><?php echo $row_users['last_name']; ?></td>
      <td class="tableTD"><?php echo $row_users['join_date']; ?></td>
      <td class="tableTD"><?php echo $row_users['role']; ?></td>
      <td class="tableTD"><?php echo $row_users['email']; ?></td>
      <td class="tableTD"><a href="edituser.php?uid=<?php echo $row_users['uid']; ?>"><img src="images/icon_edit.png" /></a></td>
      <td class="tableTD"><a href="deleteuser.php?uid=<?php echo $row_users['uid']; ?>"><img src="images/icon_remove.png" /></a></td>
    </tr>
    <?php } while ($row_users = mysql_fetch_assoc($users)); ?>
</table>
<table border="0">
  <tr>
    <td><?php if ($pageNum_users > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_users=%d%s", $currentPage, 0, $queryString_users); ?>">First</a>
          <?php } // Show if not first page ?>
    </td>
    <td><?php if ($pageNum_users > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_users=%d%s", $currentPage, max(0, $pageNum_users - 1), $queryString_users); ?>">Previous</a>
          <?php } // Show if not first page ?>
    </td>
    <td><?php if ($pageNum_users < $totalPages_users) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_users=%d%s", $currentPage, min($totalPages_users, $pageNum_users + 1), $queryString_users); ?>">Next</a>
          <?php } // Show if not last page ?>
    </td>
    <td><?php if ($pageNum_users < $totalPages_users) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_users=%d%s", $currentPage, $totalPages_users, $queryString_users); ?>">Last</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
    </div>
    <div class="TabbedPanelsContent">
    <strong>Add News Story</strong><br />

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Story:</td>
      <td><input type="text" name="story" value="" size="32" />
        <input type="submit" value="Insert record" /></td>
    </tr>
  </table>
  <?php $newsdate = date('Y-m-d'); ?>
  <input type="hidden" name="uid" value="<?php echo $_SESSION['uid']; ?>" />
  <input type="hidden" name="date" value="<?php echo $newsdate; ?>" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<table class="bodyTable" cellspacing="0" cellpadding="4">
  <tr>
  	<th colspan="5">News</th>
  </tr>
  <tr>
    <th>ID</th>
    <th>User</th>
    <th>Story</th>
    <th>Date</th>
    <th>Delete</th>
  </tr>
  <?php do { ?>
    <tr>
      <td class="tableTD"><?php echo $row_news['id']; ?></td>
      <td class="tableTD"><?php echo $row_news['first_name']." ".$row_news['last_name']; ?></td>
      <td class="tableTD"><?php echo $row_news['story']; ?></td>
      <td class="tableTD"><?php echo $row_news['date']; ?></td>
      <td class="tableTD"><a href="deletenews.php?id=<?php echo $row_news['id']; ?>"><img src="images/icon_remove.png" /></a></td>
    </tr>
    <?php } while ($row_news = mysql_fetch_assoc($news)); ?>
</table>
    </div>
    <div class="TabbedPanelsContent">
    	<table class="bodyTable" cellspacing="0" cellpadding="4" align="center">
  <tr>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Size</th>
  </tr>
  <?php do { ?>
    <tr>
      <td class="tableTD"><?php echo $row_topfive['first_name']; ?></td>
      <td class="tableTD"><?php echo $row_topfive['last_name']; ?></td>
      <td class="tableTD"><?php echo formatfilesize($row_topfive['size']); ?></td>
    </tr>
    <?php } while ($row_topfive = mysql_fetch_assoc($topfive)); ?>
</table>
    </div>
  </div>
</div>
<script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
//-->
</script>
<?php } else { ?>
You Do Not Have Permission to Access this Page<br />
Please contact an administrator if you feel this is an error.
<?php }?>
<?php require_once('footer.php'); ?>
</body>
</html>
<?php
mysql_free_result($users);
mysql_free_result($countUsers);
mysql_free_result($countImage);
mysql_free_result($countComment);
mysql_free_result($countAlbums);
mysql_free_result($news);
mysql_free_result($topfive);
mysql_free_result($dbsizequery);
?>