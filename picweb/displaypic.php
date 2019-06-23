<?php
require_once('config.php');

$uid = $_SESSION['uid'];
if ($uid < 1) {
$uid = 0;
}


$id=$_GET['pic'];//get id
mysql_select_db($database_picweb, $picweb);

$query = "SELECT * FROM image, user_album WHERE image.albumid = user_album.id AND image.id = $id AND ((image.uid = $uid) OR (user_album.public = 'true'))";
$result = mysql_query($query) or die('Error, query failed');
$content=mysql_result($result,0,"image"); //get content
$type=mysql_result($result,0,"type");//get type
//send pdf to requesting page
header("Content-type: $type");

$imagecheck = substr($type, 0, 5);

if ($imagecheck == "image"){
echo $content;
} 
mysql_free_result($result); //close database connection
?>
