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
$name=mysql_result($result,0,"name");//get name
//send pdf to requesting page
header("Content-type: $type");
header("Content-Disposition: attachment; filename=$name");
echo $content;
mysql_close($result); //close database connection
?>
