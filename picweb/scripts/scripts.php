<?php 
//Scripts.php - File that holds all of picweb's functions
//Created by Andrew Landsverk
//Last updated 7/20/2009


//Check if server is on Linux or not
function checkLinux(){
	$ostype = shell_exec("cat /proc/sys/kernel/ostype");
	$ostype = substr($ostype,0,5);
	if($ostype == "Linux"){
		$isLinux = "True";
	} else {
		$isLinux = "False";
	}
	//Debug line
	//$isLinux = "False";
	return $isLinux;
}

//returns the load average
function getLoadAvg(){
	$loadAvg = shell_exec("cat /proc/loadavg");
	$loadAvg = substr($loadAvg,0,15);
	return $loadAvg;
}

//return full server name
function fullServerName(){
	$hostname = shell_exec("hostname");
	$hostname = substr($hostname,0,-1);
	$domain = shell_exec("cat /etc/resolv.conf | grep search");
	$domain = substr($domain,7);
	$server = "http://".$hostname.".".$domain;
	return $server;
}

//formats the file size to be readable
function formatfilesize($data) {
            if ($data < 1) {
				return "0 bytes";
			}
			// bytes
            else if( $data < 1024 ) {
                return $data . " bytes";
            }
            // kilobytes
            else if( $data < 1024000 ) {
                return round( ( $data / 1024 ), 1 ) . " kB";
            }
            // megabytes
            else if( $data < 1024000000 ){
                return round( ( $data / 1024000 ), 1 ) . " MB";
            }
			//gigabytes
			else if( $data < 1024000000000){
				return round ( ( $data / 1024000000 ), 1) . " GB";
			}
			//terabytes
			else {
				return round ( ( $data / 1024000000000 ), 1) . " TB";
			}
        }

//formats the system uptime to be readable		
function format_uptime($seconds) {
  $secs = intval($seconds % 60);
  $mins = intval($seconds / 60 % 60);
  $hours = intval($seconds / 3600 % 24);
  $days = intval($seconds / 86400);
  
  if ($days > 0) {
    $uptimeString .= $days;
    $uptimeString .= (($days == 1) ? " day" : " days");
  }
  if ($hours > 0) {
    $uptimeString .= (($days > 0) ? ", " : "") . $hours;
    $uptimeString .= (($hours == 1) ? " hour" : " hours");
  }
  if ($mins > 0) {
    $uptimeString .= (($days > 0 || $hours > 0) ? ", " : "") . $mins;
    $uptimeString .= (($mins == 1) ? " minute" : " minutes");
  }
  if ($secs > 0) {
    $uptimeString .= (($days > 0 || $hours > 0 || $mins > 0) ? ", " : "") . $secs;
    $uptimeString .= (($secs == 1) ? " second" : " seconds");
  }
  return $uptimeString;
}

//function to make sure that invalid strings are not passed to the DB
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

function getBrowserType() {
$useragent = $_SERVER['HTTP_USER_AGENT'];

if (preg_match('|MSIE ([0-9].[0-9]{1,2})|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'IE';
} elseif (preg_match( '|Opera ([0-9].[0-9]{1,2})|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Opera';
} elseif(preg_match('|Firefox/([0-9\.]+)|',$useragent,$matched)) {
        $browser_version=$matched[1];
        $browser = 'Firefox';
} elseif(preg_match('|Chrome/([0-9\.]+)|',$useragent,$matched)) {
        $browser_version=$matched[1];
        $browser = 'Chrome';
} elseif(preg_match('|Safari/([0-9\.]+)|',$useragent,$matched)) {
        $browser_version=$matched[1];
        $browser = 'Safari';
} else {
        // browser not recognized!
    $browser_version = 0;
    $browser= 'other';
}

return $browser;

}

function createThumb($id){

  // Place the code to connect your Database here
 include('config.php');
 
	$uid = $_SESSION['uid'];
	if ($uid < 1) {
		$uid = 0;
	}
  // DATABASE CONNECTION
	mysql_select_db($database_picweb, $picweb);
  // Check if ID exists
  if(!is_numeric($id)) die("No image with the ID: ".$id);
  // Get data from database
$query = "SELECT type, image FROM image, user_album WHERE image.albumid = user_album.id AND image.id = $id AND ((image.uid = $uid) OR (user_album.public = 'true'))";

  $result = mysql_query($query);

  // read imagetype + -data from database
  if(mysql_num_rows($result) == 1) {
    $fileType = mysql_result($result, 0, "type");
    $fileContent = mysql_result($result, 0, "image");

    // get originalsize of image
    $im = imagecreatefromstring($fileContent);
    $width  = imagesx($im);
    $height = imagesy($im);

    // Set thumbnail-width to 100 pixel
    if ($width > 100){
	$imgw = 100;
	}else{
	$imgw=$width;
}

    // calculate thumbnail-height from given width to maintain aspect ratio
    $imgh = $height / $width * $imgw;

    // create new image using thumbnail-size
    $thumb=imagecreatetruecolor($imgw,$imgh);

    // copy original image to thumbnail
    imagecopyresized($thumb,$im,0,0,0,0,$imgw,$imgh,ImageSX($im),ImageSY($im));


    ob_start();
	ImagejpeG($thumb);
	$uploadData = ob_get_contents();
	ob_end_clean();
	$uploadData = addslashes($uploadData);
	
   mysql_select_db($database_picweb, $picweb);
		$thumbQuery = "UPDATE image SET thumb = '$uploadData' WHERE image.id = $id";
		mysql_query($thumbQuery, $picweb) or die(mysql_error());
		mysql_close($thumbQuery);
   
    // clean memory
    imagedestroy ($im);
    imagedestroy ($thumb);
  }

}

function generateThumb($id){

  // Place the code to connect your Database here
 include('config.php');
 
	$uid = $_SESSION['uid'];
	if ($uid < 1) {
		$uid = 0;
	}
  // DATABASE CONNECTION
	mysql_select_db($database_picweb, $picweb);
  // Check if ID exists
  if(!is_numeric($id)) die("No image with the ID: ".$id);
  // Get data from database
$query = "SELECT type, image FROM image, user_album WHERE image.albumid = user_album.id AND image.id = $id";

  $result = mysql_query($query);

  // read imagetype + -data from database
  if(mysql_num_rows($result) == 1) {
    $fileType = mysql_result($result, 0, "type");
    $fileContent = mysql_result($result, 0, "image");

    // get originalsize of image
    $im = imagecreatefromstring($fileContent);
    $width  = imagesx($im);
    $height = imagesy($im);

    // Set thumbnail-width to 100 pixel
    $imgw = 100;

    // calculate thumbnail-height from given width to maintain aspect ratio
    $imgh = $height / $width * $imgw;

    // create new image using thumbnail-size
    $thumb=imagecreatetruecolor($imgw,$imgh);

    // copy original image to thumbnail
    imagecopyresized($thumb,$im,0,0,0,0,$imgw,$imgh,ImageSX($im),ImageSY($im));


    ob_start();
	ImagejpeG($thumb);
	$uploadData = ob_get_contents();
	ob_end_clean();
	$uploadData = addslashes($uploadData);
	
   mysql_select_db($database_picweb, $picweb);
		$thumbQuery = "UPDATE image SET thumb = '$uploadData' WHERE image.id = $id";
		mysql_query($thumbQuery, $picweb) or die(mysql_error());
		mysql_close($thumbQuery);
   
    // clean memory
    imagedestroy ($im);
    imagedestroy ($thumb);
  }

}
?>