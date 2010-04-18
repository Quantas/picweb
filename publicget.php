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
mysql_select_db($database_picweb, $picweb);
$query_checkimage = "select image.id, image.uid, image.size, image.name, image.type from image, user_album where image.albumid = user_album.id AND image.id = $checkid AND user_album.public = 'true'";
$checkimage = mysql_query($query_checkimage, $picweb) or die(mysql_error());
$row_checkimage = mysql_fetch_assoc($checkimage);
$totalRows_checkimage = mysql_num_rows($checkimage);

mysql_select_db($database_picweb, $picweb);
$query_comments = "SELECT comment.date, comment.comment, user.first_name, user.last_name FROM user, comment WHERE comment.leftby = user.uid AND comment.picid ='".$_GET['pic']."' ORDER BY id ASC";
$comments = mysql_query($query_comments, $picweb) or die(mysql_error());
$row_comments = mysql_fetch_assoc($comments);
$totalRows_comments = mysql_num_rows($comments);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PicWeb - Public Get</title>
</head>

<body>
<?php require_once('header.php'); ?>
<?php if($totalRows_checkimage > '0') { ?>
<strong><?php echo $row_checkimage['name']; ?> - <?php echo formatfilesize($row_checkimage['size']); ?></strong><br />
<img src="displaypic.php?pic=<?php echo $_GET['pic']; ?>" width="50%" /><br />
<?php if (substr($row_checkimage['type'],0,5) == "image"){?><a href="displaypic.php?pic=<?php echo $_GET['pic']; ?>">View File</a><br /><?php } ?>
<a href="download.php?pic=<?php echo $_GET['pic']; ?>">Download File</a><br /><br />

<?php if(isset($_SESSION['uid'])){ ?>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Comment:</td>
      <td><input type="text" name="comment" value="" size="32" /> <input type="submit" value="Add Comment" /></td>
    </tr>
  </table>
  <input type="hidden" name="id" value="" />
  <input type="hidden" name="uid" value="<?php echo $row_checkimage['uid']; ?>" />
  <input type="hidden" name="picid" value="<?php echo $_GET['pic'];?>" />
  <input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>" />
  <input type="hidden" name="leftBy" value="<?php echo $_SESSION['uid']; ?>" />
  <input type="hidden" name="MM_insert" value="form1" />
</form><br />
<?php } else { echo "You must be logged in to leave a comment<br />"; }?>

<?php if ($totalRows_comments > "0"){?>
<table align="center" class="bodyTable" cellspacing="0" cellpadding="4">
  <tr>
  	<th colspan="3">Comments</th>
  </tr>
  <tr>
    <th>Date</th>
    <th>Comment</th>
    <th>Left By</th>
  </tr>
  <?php do { ?>
    <tr>
      <td class="tableTD"><?php echo $row_comments['date']; ?></td>
      <td class="tableTD"><?php echo $row_comments['comment']; ?></td>
      <td class="tableTD"><?php echo $row_comments['first_name']." ".$row_comments['last_name']; ?></td>
    </tr>
    <?php } while ($row_comments = mysql_fetch_assoc($comments)); ?>
</table>
<?php } else { echo "There are no comments yet, please leave one!"; } ?>
<?php } else { echo "This file is not public or does not exist."; } ?>
<?php require_once('footer.php'); ?>
</body>
</html>
<?php
mysql_free_result($checkimage);

mysql_free_result($comments);
?>
