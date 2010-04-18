<?php
//Create thumbnails in DB
include("config.php");

for ( $counter = 11; $counter <= 999; $counter += 1) {
	generateThumb($counter);
	echo "thumb created for ".$counter."<br>";
}

?>

