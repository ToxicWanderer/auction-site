<?php
require '/u/ashorn49/openZdatabase.php';
require 'password.php';
session_start();

if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') { 
	// we're not running on https
	header('Location: https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	exit (0);
} elseif (!isset($_SESSION['user_id'])) { 
	header('Location: login.php');
	exit (0);
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <title>Keith's Auction Site</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <meta charset="utf-8" />
  </head>

  <body>
    <header>
      <div class="loginInfo">
        You are logged in as <a href="myAccount.php"><?=$_SESSION['first_name']?></a>. (<a href="successLogout.php">Logout</a>)
      </div>

	  <h1 class="title"><a href="index.php">Keith's Auction Site</a></h1>
    </header>

<?php
// read in values from $_POST

// error checking
if($email_1 !== $email_2) {
?>
    <script>
      alert("The email addresses you entered did not match.\nPlease confirm your email address.");
      history.back();
    </script>
<?php
} else {
	// page logic here

	// report success or failure
	if(True/*all ok*/) :
		// log user in and show success message
		session_regenerate_id(true);
		$_SESSION['LOGGED_IN_ID'] = $userId;
?>
	<h2 class="pageTitle">Success!</h2>
    <div class="pageContent">
      <p>[success message]</p>

      <!-- next action -->
      <form action="myAccount.php">
        <button class="submit">View My Account</button>
      </form>
    </div>

<?php
	else:
?>
	<h2 class="pageTitle">Failure!</h2>
    <div class="pageContent">
      <p>[error message]</p>

      <!-- next action -->
      <form action="index.php">
        <button class="submit">Accept</button>
      </form>
    </div>

<?php
endif;
}
?>

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
