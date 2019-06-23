<?php
header("Content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
// Set RSS version.
echo "
<rss version=\"2.0\"> ";
// Start the XML.
echo "
  <channel>
   <title>PicWeb - News - RSS Feed</title>
   <description>The PicWeb News Feed.</description>
   <link>http://dev-unixtrack/picweb/</link>";
   // Create a connection to your database.
   require_once("config.php");
   // Query database and select the last 10 entries.
   mysql_select_db($database_picweb, $picweb);
   $query = "SELECT news.id as id, news.story as story, news.date as date, user.first_name as first_name, user.last_name as last_name FROM news, user WHERE news.uid = user.uid ORDER BY id DESC LIMIT 10";
   $data = mysql_query($query, $picweb) or die(mysql_error());
   $row = mysql_fetch_assoc($data);
   
   do{
   $fullname = $row['first_name']." ".$row['last_name'];
   // Continue with the 10 items to be included in the <item> section of the XML.
   echo "
   <item>
     <link>http://dev-unixtrack/picweb/index.php</link>
     <title>".$fullname." - ".$row['date']."</title>
     <description>".$row['story']."</description>
	 <guid>".$row['id']."</guid>
   </item>";
}while ($row = mysql_fetch_assoc($data));

echo "
  </channel>
</rss>";
?>
