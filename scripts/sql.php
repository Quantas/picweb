<?php
//sql.php - File that holds many of picweb's single value sql statements
//Created by Andrew Landsverk
//Last updated 1/8/2010

//show whos_online
function show_whos_online(){
	include('config.php');
	mysql_select_db($database_picweb, $picweb);
	$query = "SELECT * from whos_online ORDER BY username DESC";
	$online = mysql_query($query, $picweb) or die(mysql_error());
	$row_online = mysql_fetch_assoc($online);
	$totalRows_online = mysql_num_rows($online);
	
	$color="1"; ?>
  <?php do { 
	if (($row_online['username']) != ($_SESSION['username'])) {
?>
<a href="javascript:void(0)" onclick="javascript:chatWith('<?php echo $row_online['username']; ?>')"><?php echo $row_online['username']; ?></a>
	<?php } else { echo $row_online['username']; } ?>
    <?php } while ($row_online = mysql_fetch_assoc($online)); ?>
<?php
}//end function

//return the count of a user album
function checkUserAlbumCount($uid){
	include('config.php');
	mysql_select_Db($database_picweb, $picweb);
	$query = "SELECT count(id) as id FROM user_album WHERE uid = ".$uid;
	$countUserAlbum = mysql_query($query, $picweb) or die(mysql_error());
	$row_countUserAlbum = mysql_fetch_assoc($countUserAlbum);
	if ($row_countUserAlbum > 0){
	print $row_countUserAlbum['id'];
	}
	else {
	print "0";
	}
	mysql_free_result($countUserAlbum);
}//end function

//returns the count of a users image identified by $uid
function checkUserImageCount($uid){
	include('config.php');
	mysql_select_db($database_picweb, $picweb);
	$query_countUserImage = "SELECT count(id) as id FROM image WHERE `uid` = ".$uid;
	$countUserImage = mysql_query($query_countUserImage, $picweb) or die(mysql_error());
	$row_countUserImage = mysql_fetch_assoc($countUserImage);	
	print $row_countUserImage['id'];
	mysql_free_result($countUserImage);
}//end function

//returns the size of the images of a specific user aka $uid
function checkUserImageSize($uid){
	include('config.php');
	mysql_select_db($database_picweb, $picweb);
	$query_countUserImageSize = "SELECT SUM(OCTET_LENGTH(thumb)) as thumb ,sum(size) as reg FROM image WHERE `uid` = ".$uid;
	$countUserImageSize = mysql_query($query_countUserImageSize, $picweb) or die(mysql_error());
	$row_countUserImageSize = mysql_fetch_assoc($countUserImageSize);
	
	$size = $row_countUserImageSize['reg'] + $row_countUserImageSize['thumb'];
		
	$output = formatfilesize($size);
	print $output;
	mysql_free_result($countUserImageSize);
}//end function

//Returns the total size of the images in the database
function checkAllImageSize(){
	include('config.php');
	mysql_select_db($database_picweb, $picweb);
	$query_checkAllImageSize = "SELECT sum(size) as size FROM image";
	
	$checkAllImageSize = mysql_query($query_checkAllImageSize, $picweb) or die(mysql_error());
	$row_checkAllImageSize = mysql_fetch_assoc($checkAllImageSize);
		
	$output =  formatfilesize($row_checkAllImageSize['size']);
	print $output;
	mysql_free_result($checkAllImageSize);
}//end function

?>