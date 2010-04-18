<?php



if (!isset($_SESSION)) {

  session_start();

}



//Log Visit

//Logs visit for Statistics Page

if(!($logging == "false")){

	//get username - log everything else

	include('config.php');

	$browser = getBrowserType();

	if($browser == "IE"){

		$str = $_SERVER['HTTP_USER_AGENT'];

		$uArray = (explode(" ",$str));

		$uArrayL = sizeof($uArray);

		for ( $counter = 0; $counter <= $uArrayL; $counter += 1) {

			$check = substr($uArray[$counter],0,2);

			if ($check == "UN") {

				$uname = $uArray[$counter];

				$found = "true";

			}

			else {

				if (!(isset($found))){

					$uname = "Not Logged In";

				}

			}	

		}

		if (!($uname == "Not Logged In")){

			$uname = substr($uname,3);

			$uname = substr($uname,0,-2);

		}

	}else {

		if(isset($_SESSION['fullname'])){

			$uname = $_SESSION['fullname'];

		}else {

			$uname = "Not Logged In";

		}

	}

	$ip = $_SERVER['REMOTE_ADDR'];

	$host = gethostbyaddr($ip);

	$page = $_SERVER['PHP_SELF'];

	$date = date('Y-m-d');

	$date = addslashes($date);



	  $insertSQL = "INSERT INTO log (date, user, ip, host, page, browser) VALUES ('".$date."', '".$uname."', '".$ip."', '".$host."', '".$page."', '".$browser."')";

	  mysql_select_db($database_picweb, $picweb);

	  $Result1 = mysql_query($insertSQL, $picweb) or die(mysql_error());

}

//End Visit Logging Code



// ** Logout the current user. **

$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";

if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){

  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);

}



if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){

  //to fully log out a visitor we need to clear the session varialbles

  $_SESSION['MM_Username'] = NULL;

  $_SESSION['MM_UserGroup'] = NULL;

  $_SESSION['PrevUrl'] = NULL;

  $_SESSION['uid'] = NULL;

  $_SESSION['fullname'] = NULL;

  $_SESSION['role'] = NULL;

  unset($_SESSION['MM_Username']);

  unset($_SESSION['MM_UserGroup']);

  unset($_SESSION['PrevUrl']);

  unset($_SESSION['uid']);

  unset($_SESSION['fullname']);

  unset($_SESSION['role']);

	

  $logoutGoTo = "index.php";

  if ($logoutGoTo) {

    header("Location: $logoutGoTo");

    exit;

  }

}

?>

<link href="styles/style.css" rel="stylesheet" type="text/css" />
<!-- script type="text/javascript" src="js/prototype.js"></script -->
<script type="text/javascript" src="js/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="js/lightbox.js"></script>


<div id="container">

<!--BEGIN HEADER-->

<div id="header">

<table class="mainTable"> 

        <tr> 

            <td class="headerLeft"> 

                 <a href="index.php"><img src="images/logo.png" border="0" height="40" /></a>          </td> 

<td class="headerRight">

            			<?php if (isset($_SESSION['uid'])){ 

                        echo "Welcome, ".$_SESSION['fullname']; }?>

          </td> 

        </tr>  

    </table> 

</div>

    <div id="menu">

    	<?php if (isset($_SESSION['uid'])){ ?>

						<a href="index.php">Home</a> | 

                        <a href="commentfeed.php">Comment Feed</a> | 

                        <a href="gallery.php">Gallery</a> | 

                        <a href="public.php">Public Albums</a> | 

                        <a href="manage.php">Manage</a> | 

                        <?php if ($_SESSION['role'] == "admin"){ 

							echo "<a href=\"admin.php\">Admin</a> | "; 

						}?>

                         <a href="<?php echo $logoutAction ?>">Logout</a>

                         <?php }else{ ?>

                         	<a href="public.php">Public Albums</a> | 

                         	<a href="commentfeed.php">Comment Feed</a> | 

                            <a href="register.php">Register</a> | 

                            <a href="login.php">Login</a> 

						 <?php } ?>    

    </div>

<!--END HEADER-->

<div id="content">