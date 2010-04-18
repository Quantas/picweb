<?php require_once('config.php'); 

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="register.php";
  $loginUsername = $_POST['username'];
  $LoginRS__query = sprintf("SELECT username FROM `user` WHERE username=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_picweb, $picweb);
  $LoginRS=mysql_query($LoginRS__query, $picweb) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $password=$_POST['password'];
  $password=md5($password);
  $date=date("Y-m-d");
  $role="user";
  
  $insertSQL = sprintf("INSERT INTO user (username, first_name, last_name, password, join_date, role, email) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['lastName'], "text"),
                       GetSQLValueString($password, "text"),
					   GetSQLValueString($date, "date"),
					   GetSQLValueString($role, "text"),
					   GetSQLValueString($_POST['email'], "text"));

  mysql_select_db($database_picweb, $picweb);
  $Result1 = mysql_query($insertSQL, $picweb) or die(mysql_error());

  $insertGoTo = "login.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PicWeb - Register Account</title>
</head>

<body>
<?php require_once('header.php'); ?>
<center>
<img src="images/picweblogo.jpg" width="300"/><br /><br>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <label>First Name:
  <input type="text" name="FirstName" id="FirstName" />
  </label>
  <p>
    <label>Last Name:
    <input type="text" name="lastName" id="lastName" />
    </label>
  </p>
  <p>
    <label>Username:
    <input type="text" name="username" id="username" />
    </label>
  </p>
  <p>
    <label>Password:
    <input type="password" name="password" id="password" />
    </label>
  </p>
    <p>
    <label>Email:
    <input type="text" name="email" id="email" />
    </label>
  </p>
  <p>
    <label>
    <input type="submit" name="register" id="register" value="Submit" />
    </label>
</p>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<?php if (isset($_GET['requsername'])){
echo "<font color=\"red\">The username ".$_GET['requsername']." has already been taken.</font>";
}?>
<?php require_once('footer.php'); ?>
</center>
</body>
</html>
