<?php 

function generateNews(){
	include('config.php');

	mysql_select_db($database_picweb, $picweb);
	$query_news = "SELECT news.id as id, news.story as story, news.date as date, user.first_name as first_name, user.last_name as last_name FROM news, user WHERE news.uid = user.uid ORDER BY news.id DESC LIMIT 4";
	$news = mysql_query($query_news, $picweb) or die(mysql_error());
	$row_news = mysql_fetch_assoc($news);
	$totalRows_news = mysql_num_rows($news);
	
	$color="1"; ?>
<table align="center" class="bodyTable" cellspacing="0" cellpadding="4" width="400px">
  <tr>
  	<th colspan="2">News</th>
  </tr>
  <?php do { ?>
    <tr class="row<?php echo $color;?>">
      <td><strong><?php echo $row_news['first_name']." ".$row_news['last_name']; ?> - <?php echo $row_news['date']; ?></strong></td>
      <tr class="row<?php echo $color;?>"><td class="tableTD" colspan="2"><?php echo $row_news['story']; ?></td></tr>
    </tr>
       <?php if($color == "1"){
			$color="2";
		} else {$color="1";} ?>
    <?php } while ($row_news = mysql_fetch_assoc($news)); ?>
</table>
<?php 
}//end Function
?>