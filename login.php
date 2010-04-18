<?php require_once('config.php'); 



mysql_select_db($database_picweb, $picweb);

$query_login = "SELECT * FROM `user`";

$login = mysql_query($query_login, $picweb) or die(mysql_error());

$row_login = mysql_fetch_assoc($login);

$totalRows_login = mysql_num_rows($login);



$loginFormAction = $_SERVER['PHP_SELF'];

if (isset($_GET['accesscheck'])) {

  $_SESSION['PrevUrl'] = $_GET['accesscheck'];

}



if (isset($_POST['Username:'])) {

  $loginUsername=$_POST['Username:'];

  $password=$_POST['password'];

  $password=md5($password);

  $MM_fldUserAuthorization = "";

  $MM_redirectLoginSuccess = "gallery.php";

  $MM_redirectLoginFailed = "login.php?failed=true";

  $MM_redirecttoReferrer = true;

  mysql_select_db($database_picweb, $picweb);

  

  $LoginRS__query=sprintf("SELECT * FROM `user` WHERE username=%s AND password=%s",

    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 

   

  $LoginRS = mysql_query($LoginRS__query, $picweb) or die(mysql_error());

  $loginFoundUser = mysql_num_rows($LoginRS);

  if ($loginFoundUser) {

     $loginStrGroup = "";

	 $uid = mysql_result($LoginRS,0,'uid');

	 $firstName = mysql_result($LoginRS,0,'first_name');

     $lastName = mysql_result($LoginRS,0,'last_name');

	 $role = mysql_result($LoginRS,0,'role');

	 $fullName = $firstName." ".$lastName;

	

    //declare two session variables and assign them

    $_SESSION['MM_Username'] = $loginUsername;

    $_SESSION['MM_UserGroup'] = $loginStrGroup;

	$_SESSION['fullname'] = $fullName;

	$_SESSION['uid'] = $uid;

	$_SESSION['role'] = $role;	

	

	//EMAIL after login

	//$to = mysql_result($LoginRS,0,'email');

	//$subject = "Login Confirmation";

	//$body = "This message has been sent to confirm that you have successfully logged in to PicWeb.";

	//if (mail($to, $subject, $body)) {

	//  echo("<p>Message successfully sent!</p>");

	// } else {

	//  echo("<p>Message delivery failed...</p>");

	// }

      



    if (isset($_SESSION['PrevUrl']) && true) {

      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	

    }

    header("Location: " . $MM_redirectLoginSuccess );

  }

  else {

    header("Location: ". $MM_redirectLoginFailed );

  }

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>PicWeb - Login</title>

</head>



<body>

<?php require_once('header.php'); ?>

<center>

<img src="images/logo.png" width="300"/>

<form name="login" action="<?php echo $loginFormAction; ?>" method="POST">



  <p>Login</p>



  <p>Username: 



    <input name="Username:" type="text" />



  </p>



  <p>Password: 



  <input type="password" name="password" id="textfield" />



</p>



<p>



  <label>	



  <input type="submit" name="Login" id="Login" value="Submit" />



  </label>



</p></form>

<?php if(isset($_GET['failed'])){?>

<font color="red">Invalid Username/Password Combination</font><?php } ?>

<?php require_once('footer.php'); ?>

</center>

</body>

</html>

<?php

mysql_free_result($login);

?>

