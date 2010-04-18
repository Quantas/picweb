<?php
require_once('config.php');

$uid = $_SESSION['uid'];
if ($uid < 1) {
$uid = 0;
}


$id=$_GET['id'];//get id
mysql_select_db($database_picweb, $picweb);

$query = "SELECT thumb,type FROM image, user_album WHERE image.albumid = user_album.id AND image.id = $id AND ((image.uid = $uid) OR (user_album.public = 'true'))";
$result = mysql_query($query) or die('Error, query failed');
$content=mysql_result($result,0,"thumb"); //get content
$type=mysql_result($result,0,"type");//get type
//send pdf to requesting page


$imagecheck = substr($type, 0, 5);
	if ($imagecheck == "image"){
		header("Content-type: $type");
		echo $content;
	}
	else {
	
	$query = "SELECT thumb,type FROM image, user_album WHERE user_album.uid = image.uid AND image.uid = 0 LIMIT 1";
	$result = mysql_query($query) or die('Error, query failed');
	$content=mysql_result($result,0,"thumb"); //get content
	$type=mysql_result($result,0,"type");
	header("Content-type: $type");
	echo $content;
	}
mysql_free_result($result); //close database connection
?>