</div>
<?php if (isset($_SESSION['username'])) { ?>
<div id="whos_online">
Who's Online:<?php show_whos_online(); ?>
</div>
<?php } ?>
<div id="footer">
&copy; 2010 PicWeb - All Rights Reserved<br />
Site Designed by <a href="mailto:dewdew@gmail.com">Andrew Landsverk</a><br />
<?php 
$browser = getBrowserType();
if($browser == "IE"){ ?>
Please upgrade to a more modern browser such as Firefox/Chrome/Safari.<br />
<?php } ?>
<?php
$time = microtime();
$time = explode(" ", $time);
$time = $time[1] + $time[0];
$finish = $time;
$totaltime = ($finish - $start);
$totaltime = ($totaltime * 1000);
printf ("<font size=\"1\">Page generated in %f ms. </font>", $totaltime);
?>
</div>
</div>
</div>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/chat.js"></script>