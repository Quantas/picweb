<title>PicWeb - Upload</title><?php 
require_once('config.php');

require_once('header.php');



if(isset($_POST['upload']))

{

	//get file information --step1

	$fileName = $_FILES['userfile']['name'];

	$fileType = $_FILES['userfile']['type'];

	$tmpName = $_FILES['userfile']['tmp_name'];

	$fileSize = $_FILES['userfile']['size'];

	$uploadDate = date("Y-m-d");

	

		if(($_FILES['userfile']['size']) > 0){

			$userID = $_SESSION['uid'];

			$albumID = $_POST['albumid'];

			

			

			

			

			if(!get_magic_quotes_gpc())

			{

				$fileName = addslashes($fileName);

			}

			//get file content -- step1

			$fp = fopen($tmpName, 'r');

			$content = fread($fp, $fileSize);

			$content = addslashes($content);

			fclose($fp);

			

			mysql_select_db($database_picweb, $picweb);

			

			//insert into database --step3

			$query = "INSERT INTO image (uid,albumid,date,name,type,size,image) ".

			"VALUES('$userID','$albumID','$uploadDate','$fileName','$fileType','$fileSize','$content')";

			mysql_query($query) or die(mysql_error());

			

			//return insert information to client

			$id= mysql_insert_id();

			

			//create Thumbnail

			createThumb($id);

			echo "<META http-equiv='refresh' content='5;URL=gallery.php?albumid=$albumID'>";
			echo "Redirecting in 5 seconds";
			echo "File<b> $fileName</b> uploaded successfully.<br><a href=\"gallery.php?albumid=$albumID\">Click here</a> to view the pictures";

			

			

			} 

			else {

				echo "No Picture Selected. Please press back"; 

 			}

}

else {

echo "An Error has Occured (Most likely, the file is too large). Please press back.";

}		

require_once('footer.php');

mysql_free_result($query); //close connection

?>

