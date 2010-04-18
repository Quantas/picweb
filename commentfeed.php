<?php require_once('config.php');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="alternate" type="application/rss+xml"  href="rss/rsscomment.php" title="PicWeb - Comment Feed">
<title>PicWeb - Comment Feed</title>
</head>

<body>
<?php require_once('header.php');?>

<?php generateCommentFeed(); ?>

<?php require_once('footer.php');?>
</body>
</html>
