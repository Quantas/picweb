    <?php
	include('config.php');
	$uid = $_SESSION['uid'];
	//$albumid = $_SESSION['currentAlbum'];
	//$uid = 1;
	$albumid = $_GET['albumid'];
	mysql_select_db($database_picweb, $picweb);

	$query_gallery2 = "SELECT id, name, uid, albumid FROM image WHERE image.uid = '".$uid."' AND image.albumid = '".$albumid."'";

	$gallery2 = mysql_query($query_gallery2, $picweb) or die(mysql_error());

	$row_gallery2 = mysql_fetch_assoc($gallery2);

	$totalRows_gallery2 = mysql_num_rows($gallery2);
	?>
    
    <?xml version="1.0" encoding="utf-8" standalone="yes"?>
    <rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/" 
        xmlns:atom="http://www.w3.org/2005/Atom">
        <channel>
        <?php do { ?>
            <item>
                <title><?php echo $row_gallery2['name']; ?></title>
                <link><?php echo "userget.php?albumid=".$row_gallery2['albumid']."&pic=".$row_gallery2['id'] ?></link>
                <media:thumbnail url="thumb.php?id=<?php echo $row_gallery2['id']; ?>"/>
                <media:content url="displaypic.php?pic=<?php echo $row_gallery2['id']; ?>"/>
            </item> 
        <?php } while ($row_gallery2 = mysql_fetch_assoc($gallery2)); ?> 
        </channel>
        </rss>
