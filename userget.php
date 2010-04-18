<?php require_once('config.php'); 

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO `comment` (id, picid, uid,`date`, `comment`, leftBy) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id'], "int"),
                       GetSQLValueString($_POST['picid'], "int"),
					   GetSQLValueString($_POST['uid'], "int"),
                       GetSQLValueString($_POST['date'], "date"),
                       GetSQLValueString($_POST['comment'], "text"),
                       GetSQLValueString($_POST['leftBy'], "int"));

  mysql_select_db($database_picweb, $picweb);
  $Result1 = mysql_query($insertSQL, $picweb) or die(mysql_error());

  $insertGoTo = $_SERVER['HTTP_REFERER'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$checkid = $_GET['pic'];
$user = $_SESSION['uid'];
mysql_select_db($database_picweb, $picweb);
$query_checkimage = "select image.id, image.type from image, user_album where image.albumid = user_album.id AND image.id = $checkid AND image.uid = $user";
$checkimage = mysql_query($query_checkimage, $picweb) or die(mysql_error());
$row_checkimage = mysql_fetch_assoc($checkimage);
$totalRows_checkimage = mysql_num_rows($checkimage);

mysql_select_db($database_picweb, $picweb);
$query_comments = "SELECT comment.id as id, comment.date, comment.comment, user.first_name, user.last_name FROM user, comment WHERE comment.leftby = user.uid AND comment.picid ='".$_GET['pic']."' ORDER BY id ASC";
$comments = mysql_query($query_comments, $picweb) or die(mysql_error());
$row_comments = mysql_fetch_assoc($comments);
$totalRows_comments = mysql_num_rows($comments);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PicWeb - User Get</title>
</head>

<body>
<?php require_once('header.php'); ?>
<?php if($totalRows_checkimage > '0') { ?>
<img src="displaypic.php?pic=<?php echo $_GET['pic']; ?>" width="50%" />
<br />
<a href="editpic.php?albumid=<?php echo $_GET['albumid']; ?>&pic=<?php echo $_GET['pic']; ?>">Edit File</a><br />
<?php if (substr($row_checkimage['type'],0,5) == "image"){?><a href="displaypic.php?pic=<?php echo $_GET['pic']; ?>">View File</a><br /><?php } ?>
<a href="download.php?pic=<?php echo $_GET['pic']; ?>">Download File</a>
<?php } else { echo "This file does not exist or does not belong to you."; } ?>

<?php if(isset($_SESSION['uid'])){ ?>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Comment:</td>
      <td><input type="text" name="comment" value="" size="32" /> <input type="submit" value="Add Comment" /></td>
    </tr>
  </table>
  <input type="hidden" name="id" value="" />
  <input type="hidden" name="uid" value="<?php echo $_SESSION['uid'];?>" />
  <input type="hidden" name="picid" value="<?php echo $_GET['pic'];?>" />
  <input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>" />
  <input type="hidden" name="leftBy" value="<?php echo $_SESSION['uid']; ?>" />
  <input type="hidden" name="MM_insert" value="form1" />
</form><br />
<?php } else { echo "You must be logged in to leave a comment"; }?>
<?php if ($totalRows_comments > "0"){ ?>
<table align="center" class="bodyTable" cellspacing="0" cellpadding="4">
  <tr>
  	<th colspan="4">Comments</th>
  </tr>
  <tr>
    <th>Date</th>
    <th>Comment</th>
    <th>Left By</th>
    <th>Delete</th>
  </tr>
  <?php do { ?>
    <tr>
      <td class="tableTD"><?php echo $row_comments['date']; ?></td>
      <td class="tableTD"><?php echo $row_comments['comment']; ?></td>
      <td class="tableTD"><?php echo $row_comments['first_name']." ".$row_comments['last_name']; ?></td>
      <td class="tableTD"><a href="deletecomment.php?pic=<?php echo $_GET['pic']; ?>&albumid=<?php echo $_GET['albumid']; ?>&id=<?php echo $row_comments['id']; ?>"><img src="images/icon_remove.png" border="0" /></a></td>
    </tr>
    <?php } while ($row_comments = mysql_fetch_assoc($comments)); ?>
</table>
<?php } else { echo "There are no comments.";} ?>
<?php require_once('footer.php'); ?>
</body>
</html>
<?php
mysql_free_result($checkimage);
?>
