<?php require_once('config.php'); 


mysql_select_db($database_picweb, $picweb);
$query_log = "SELECT * FROM log";
$log = mysql_query($query_log, $picweb) or die(mysql_error());
$row_log = mysql_fetch_assoc($log);
$totalRows_log = mysql_num_rows($log);

mysql_select_db($database_picweb, $picweb);
$query_Top5Browser = "select COUNT(id) as count, browser from log group by browser order by count DESC Limit 5";
$Top5Browser = mysql_query($query_Top5Browser, $picweb) or die(mysql_error());
$row_Top5Browser = mysql_fetch_assoc($Top5Browser);
$totalRows_Top5Browser = mysql_num_rows($Top5Browser);

mysql_select_db($database_picweb, $picweb);
$query_Top5Hosts = "select COUNT(id) as count, ip, host from log group by ip order by count DESC Limit 5";
$Top5Hosts = mysql_query($query_Top5Hosts, $picweb) or die(mysql_error());
$row_Top5Hosts = mysql_fetch_assoc($Top5Hosts);
$totalRows_Top5Hosts = mysql_num_rows($Top5Hosts);

mysql_select_db($database_picweb, $picweb);
$query_Top5Pages = "select COUNT(id) as count, page from log group by page order by count DESC Limit 5";
$Top5Pages = mysql_query($query_Top5Pages, $picweb) or die(mysql_error());
$row_Top5Pages = mysql_fetch_assoc($Top5Pages);
$totalRows_Top5Pages = mysql_num_rows($Top5Pages);

mysql_select_db($database_picweb, $picweb);
$query_Top5Users = "select count(id) as count, user from log group by user order by count DESC limit 5";
$Top5Users = mysql_query($query_Top5Users, $picweb) or die(mysql_error());
$row_Top5Users = mysql_fetch_assoc($Top5Users);
$totalRows_Top5Users = mysql_num_rows($Top5Users);

mysql_select_db($database_picweb, $picweb);
$query_RequestsPerDay = "Select count(id) as cnt, date from log group by date order by cnt DESC limit 5 ";
$RequestsPerDay = mysql_query($query_RequestsPerDay, $picweb) or die(mysql_error());
$row_RequestsPerDay = mysql_fetch_assoc($RequestsPerDay);
$totalRows_RequestsPerDay = mysql_num_rows($RequestsPerDay);

mysql_select_db($database_picweb, $picweb);
$query_topUsersByHost = "select count(host) as hostCount, host, user from log  group by user,host order by hostCount DESC";
$topUsersByHost = mysql_query($query_topUsersByHost, $picweb) or die(mysql_error());
$row_topUsersByHost = mysql_fetch_assoc($topUsersByHost);
$totalRows_topUsersByHost = mysql_num_rows($topUsersByHost);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PicWeb - Statistics</title>
</head>
<body>
<?php require_once('header.php'); ?>
<?php  if($_SESSION['role'] == 'admin') { ?>
  <table class="bodyTable" cellpadding="4" cellspacing="0" align="center">
    <tr>
      <th>count</th>
      <th>browser</th>
    </tr>
    <?php do { ?>
      <tr>
        <td class="tableTD"><?php echo $row_Top5Browser['count']; ?></td>
        <td class="tableTD"><?php echo $row_Top5Browser['browser']; ?></td>
      </tr>
      <?php } while ($row_Top5Browser = mysql_fetch_assoc($Top5Browser)); ?>
  </table><br />
  <table class="bodyTable" cellpadding="4" cellspacing="0" align ="center">
    <tr>
      <th>count</th>
      <th>ip</th>
      <th>host</th>
    </tr>
    <?php do { ?>
      <tr>
        <td class="tableTD"><?php echo $row_Top5Hosts['count']; ?></td>
        <td class="tableTD"><?php echo $row_Top5Hosts['ip']; ?></td>
        <td class="tableTD"><?php echo $row_Top5Hosts['host']; ?></td>
      </tr>
      <?php } while ($row_Top5Hosts = mysql_fetch_assoc($Top5Hosts)); ?>
  </table><br />
  <table class="bodyTable" cellpadding="4" cellspacing="0" align="center">
    <tr>
      <th>count</th>
      <th>page</th>
    </tr>
    <?php do { ?>
      <tr>
        <td class="tableTD"><?php echo $row_Top5Pages['count']; ?></td>
        <td class="tableTD"><?php echo $row_Top5Pages['page']; ?></td>
      </tr>
      <?php } while ($row_Top5Pages = mysql_fetch_assoc($Top5Pages)); ?>
  </table><br />
  <table class="bodyTable" align="center" cellpadding="4" cellspacing="0">
    <tr>
      <th>count</th>
      <th>user</th>
    </tr>
    <?php do { ?>
      <tr>
        <td class="tableTD"><?php echo $row_Top5Users['count']; ?></td>
        <td class="tableTD"><?php echo $row_Top5Users['user']; ?></td>
      </tr>
      <?php } while ($row_Top5Users = mysql_fetch_assoc($Top5Users)); ?>
  </table><br />
  <table class="bodyTable" align="center" cellpadding="4" cellspacing="0">
    <tr>
      <th>count</th>
      <th>date</th>
    </tr>
    <?php do { ?>
      <tr>
        <td class="tableTD"><?php echo $row_RequestsPerDay['cnt']; ?></td>
        <td class="tableTD"><?php echo $row_RequestsPerDay['date']; ?></td>
      </tr>
      <?php } while ($row_RequestsPerDay = mysql_fetch_assoc($RequestsPerDay)); ?>
  </table><br />
  <table class="bodyTable" cellpadding="4" cellspacing="0" align="center">
    <tr>
      <th>hostCount</th>
      <th>host</th>
      <th>user</th>
    </tr>
    <?php do { ?>
      <tr>
        <td class="tableTD"><?php echo $row_topUsersByHost['hostCount']; ?></td>
        <td class="tableTD"><?php echo $row_topUsersByHost['host']; ?></td>
        <td class="tableTD"><?php echo $row_topUsersByHost['user']; ?></td>
      </tr>
      <?php } while ($row_topUsersByHost = mysql_fetch_assoc($topUsersByHost)); ?>
  </table>
  <?php echo shell_exec("/var/log/apache2/error_log");?>
  <?php  } else { echo "<font color=red>You do not have Admin rights. </font>"; } ?>
  <?php echo $_SERVER['HTTP_USER_AGENT']; ?>
  <?php require_once('footer.php'); ?>
</body>
</html>
<?php
mysql_free_result($log);
mysql_free_result($Top5Browser);
mysql_free_result($Top5Hosts);

mysql_free_result($Top5Pages);

mysql_free_result($Top5Users);

mysql_free_result($RequestsPerDay);

mysql_free_result($topUsersByHost);
?>
