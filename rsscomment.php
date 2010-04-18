<?php 
   require_once("config.php");
header("Content-type: text/xml"); 
 echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<rss version=\"2.0\">
  <channel>
   <title>PicWeb - Comment Feed</title>
   <description>The PicWeb Comment Feed</description>
   <link>http://dev-unixtrack/picweb/commentfeed.php</link>";
   mysql_select_db($database_picweb, $picweb);
   $query = "SELECT comment.id as cid, image.id, image.albumid, comment.date, comment.comment, image.name, user.first_name, user.last_name FROM comment, user, image, user_album WHERE image.albumid = user_album.id AND user_album.public = 'true' AND image.id = comment.picid AND user.uid = comment.leftby ORDER BY cid DESC LIMIT 20";
   $data = mysql_query($query, $picweb) or die(mysql_error());
   $row = mysql_fetch_assoc($data);
   do{
   $fullname = $row['first_name']." ".$row['last_name'];
	$pic = $row['id'];
	$albumid = $row['albumid'];
	$date = $row['date'];
	$cid = $row['cid'];
	$name = $row['name'];
	$comment = $row['comment'];
echo"<item>
     <link>http://dev-unixtrack/picweb/publicget.php?pic=$pic&albumid=$albumid</link>
     <title>$fullname - $date</title>
     <description>$name - $comment</description>
	 <guid>$cid</guid>
   </item>";
    }while ($row = mysql_fetch_assoc($data));
echo"  </channel>
</rss>";
?>