<?php

//generate User Gallery page

function generateGallery($uid, $albumid){

	include('config.php');

	$currentPage = $_SERVER["PHP_SELF"];

	$_SESSION['currentGallery'] = $albumid;

	$maxRows_gallery = 15;

	$pageNum_gallery = 0;

	if (isset($_GET['pageNum_gallery'])) {

	  $pageNum_gallery = $_GET['pageNum_gallery'];

	}

	$startRow_gallery = $pageNum_gallery * $maxRows_gallery;

	

	mysql_select_db($database_picweb, $picweb);

	$query_gallery = "SELECT id, name, uid, albumid FROM image WHERE image.uid = '".$uid."' AND image.albumid = '".$albumid."'";

	$query_limit_gallery = sprintf("%s LIMIT %d, %d", $query_gallery, $startRow_gallery, $maxRows_gallery);

	$gallery = mysql_query($query_limit_gallery, $picweb) or die(mysql_error());

	$row_gallery = mysql_fetch_assoc($gallery);

	

	mysql_select_db($database_picweb, $picweb);

	$query_albumcheck = "SELECT * FROM user_album WHERE uid = '".$uid."' and id ='".$albumid."'";

	$albumcheck = mysql_query($query_albumcheck, $picweb) or die(mysql_error());

	$row_albumcheck = mysql_fetch_assoc($albumcheck);

	$totalRows_albumcheck = mysql_num_rows($albumcheck);

	

	$colname_albums = "-1";

	if (isset($_SESSION['uid'])) {

	  $colname_albums = $_SESSION['uid'];

	}

	mysql_select_db($database_picweb, $picweb);

	$query_albums = sprintf("SELECT * FROM user_album WHERE `uid` = %s ORDER BY name ASC", GetSQLValueString($colname_albums, "int"));

	$albums = mysql_query($query_albums, $picweb) or die(mysql_error());

	$row_albums = mysql_fetch_assoc($albums);

	$totalRows_albums = mysql_num_rows($albums);

	

	

	if (isset($_GET['totalRows_gallery'])) {

	  $totalRows_gallery = $_GET['totalRows_gallery'];

	} else {

	  $all_gallery = mysql_query($query_gallery);

	  $totalRows_gallery = mysql_num_rows($all_gallery);

	}

	$totalPages_gallery = ceil($totalRows_gallery/$maxRows_gallery)-1;

	

	$queryString_gallery = "";

	if (!empty($_SERVER['QUERY_STRING'])) {

	  $params = explode("&", $_SERVER['QUERY_STRING']);

	  $newParams = array();

	  foreach ($params as $param) {

		if (stristr($param, "pageNum_gallery") == false && 

			stristr($param, "totalRows_gallery") == false) {

		  array_push($newParams, $param);

		}

	  }

	  if (count($newParams) != 0) {

		$queryString_gallery = "&" . htmlentities(implode("&", $newParams));

	  }

	}

	$queryString_gallery = sprintf("&totalRows_gallery=%d%s", $totalRows_gallery, $queryString_gallery); ?>

	<table width="100%">

	<td width="16%" valign="top" align="center"><div id="scroll">

	<?php if (($totalRows_albums) > '0') {?>

	<table class="bodyTable" cellspacing="0" cellpadding="4">

	  <tr>

		<th class="tableTD"><strong>Albums</strong></th>

	  </tr>

	  <?php $color="1"; ?>

	  <?php do { ?>

		<tr class="row<?php echo $color;?>">

		  <td class="tableTD">

			   <a href="gallery.php?albumid=<?php echo $row_albums['id']; ?>"><?php echo $row_albums['name']; ?></a>

		  </td>

		</tr>

		<?php if($color == "1"){

				$color="2";

			} else {$color="1";} ?>

		<?php } while ($row_albums = mysql_fetch_assoc($albums)); ?>

	</table>

	<?php }else{ 

		echo "<br />You have no albums<br />";}?><br />

	</div></td>

	

	<td width="84%" align="center" valign="top">

	<?php if (($totalRows_albumcheck) > '0'){

	?>

	<strong><?php echo $row_albumcheck['name']; ?></strong><br />

	<table class="galleryTable" cellspacing="0" cellpadding="4">

	<tr>

	<?php if (($totalRows_gallery) > '0') {?>

		<?php $count = 0; ?>

		<?php do {?>

		  <?php if ($count > 4) { echo "</tr><tr>"; $count = 0;}?><td><a href="displaypic.php?pic=<?php echo $row_gallery['id']; ?>" rel="lightbox[gallery]"><img src="thumb.php?id=<?php echo $row_gallery['id']; ?>"></a><br /><a href="userget.php?albumid=<?php echo $row_albumcheck['id']; ?>&pic=<?php echo $row_gallery['id']; ?>"><?php echo $row_gallery['name']; ?></a><?php $count = $count + 1; ?></td>

		<?php } while ($row_gallery = mysql_fetch_assoc($gallery)); ?>

		<?php } else { echo "This Album is empty. </tr>"; } ?>

		</table><br />

		<table border="0" align="center">

		  <tr>

			<td><?php if ($pageNum_gallery > 0) { // Show if not first page ?>

				  <a href="<?php printf("%s?pageNum_gallery=%d%s", $currentPage, 0, $queryString_gallery); ?>">First</a>

				  <?php } // Show if not first page ?>

			</td>

			<td><?php if ($pageNum_gallery > 0) { // Show if not first page ?>

				  <a href="<?php printf("%s?pageNum_gallery=%d%s", $currentPage, max(0, $pageNum_gallery - 1), $queryString_gallery); ?>">Previous</a>

				  <?php } // Show if not first page ?>

			</td>

			<td><?php if ($pageNum_gallery < $totalPages_gallery) { // Show if not last page ?>

				  <a href="<?php printf("%s?pageNum_gallery=%d%s", $currentPage, min($totalPages_gallery, $pageNum_gallery + 1), $queryString_gallery); ?>">Next</a>

				  <?php } // Show if not last page ?>

			</td>

			<td><?php if ($pageNum_gallery < $totalPages_gallery) { // Show if not last page ?>

				  <a href="<?php printf("%s?pageNum_gallery=%d%s", $currentPage, $totalPages_gallery, $queryString_gallery); ?>">Last</a>

				  <?php } // Show if not last page ?>

			</td>

		  </tr>

		</table>

		<br />

	<?php if (($totalRows_gallery) > '0') {?>

	Pictures <?php echo ($startRow_gallery + 1) ?> to <?php echo min($startRow_gallery + $maxRows_gallery, $totalRows_gallery) ?> of <?php echo $totalRows_gallery ?> <br /><br />

	<?php } ?>

	Upload New Picture:<br />

	<form action="upload.php" method="post" enctype="multipart/form-data">

	<input name="albumid" type="hidden" id="albumid" value="<?php echo $albumid; ?>">

	<input name="userfile" type="file" id="userfile">

	<input name="upload" type="submit" id="upload" value="Upload Picture">

	</form>

		   <?php } else {

	echo "Please Select an album from the left.";}?>

	 </td>

	</table>

<?php 

}//end Function 



?>