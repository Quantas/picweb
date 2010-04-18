</div>

<div id="footer">
&copy; 2009 PicWeb - All Rights Reserved<br />

Site Designed by <a href="mailto:landsverka@my.uwstout.edu">Andrew Landsverk</a><br />

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
  <br />  <a href="http://jigsaw.w3.org/css-validator/check/referer">
        <img style="border:0;"
            src="images/css-valid.png"
            alt="Valid CSS" /></a>
</div>

</div>