<?php
function generateCommentFeed(){
	include('config.php');
	mysql_select_db($database_picweb, $picweb);
	$query_commentFeed = "SELECT image.id, image.albumid, comment.date, comment.comment, image.name, user.first_name, user.last_name FROM comment, user, image, user_album WHERE image.albumid = user_album.id AND user_album.public = 'true' AND image.id = comment.picid AND user.uid = comment.leftby ORDER BY comment.id DESC LIMIT 25";
	$commentFeed = mysql_query($query_commentFeed, $picweb) or die(mysql_error());
	$row_commentFeed = mysql_fetch_assoc($commentFeed);
	$totalRows_commentFeed = mysql_num_rows($commentFeed);
?>
	<table align="center" class="bodyTable" cellspacing="0" cellpadding="4">
  <tr>
    <th class="tableTD" colspan="2">Recent Comments</th>
  </tr>
  <?php $color="1"; ?>
  <?php do { 
  $fullName = $row_commentFeed['first_name']." ".$row_commentFeed['last_name'];
  ?>
    <tr class="row<?php echo $color;?>">
    	<td class="tableTD"><a href="publicget.php?pic=<?php echo $row_commentFeed['id']; ?>&albumid=<?php echo $row_commentFeed['albumid']; ?>"><img src="thumb.php?id=<?php echo $row_commentFeed['id']; ?>" /></a></td>
      <td class="tableTD"><strong><?php echo $fullName." - ".$row_commentFeed['date']; ?></strong><br />
      <a href="publicget.php?pic=<?php echo $row_commentFeed['id']; ?>&albumid=<?php echo $row_commentFeed['albumid']; ?>"><?php echo "<strong>".$row_commentFeed['name']."</strong></a> - ".$row_commentFeed['comment']; ?>
      </td>
    </tr>
    <?php if($color == "1"){
			$color="2";
		} else {$color="1";} ?>
    <?php } while ($row_commentFeed = mysql_fetch_assoc($commentFeed)); ?>
</table>
<?php 
	mysql_free_result($commentFeed);
}//End function
?>