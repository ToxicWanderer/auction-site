<?php
require 'db.php';
require 'password.php';
session_start();

if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== "on") {
    header('HTTP/1.1 403 Forbidden: TLS Required');
	header('Location: index.php');
    exit(1);
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <title>Keith's Auction Site</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <meta charset="utf-8" />
  </head>

<?php
$username = htmlspecialchars($_POST['username']);
$pwd = htmlspecialchars($_POST['pwd']);

// error checking
if(!isset($_POST['username'])) {
?>
    <script>
      alert("Username not provided.\nPlease provide your username.");
      history.back();
    </script>
<?php
} elseif(!isset($_POST['pwd'])) {
?>
    <script>
      alert("Password not provided.\nPlease provide your password.");
      history.back();
    </script>
<?php
} else {
	// page logic
	$userIdQuery = $database->prepare('
		SELECT
			USER_ID,
			FIRST_NAME,
			LAST_NAME
			FROM PERSON
			WHERE USERNAME = :username;		
		');
	$userIdQuery->bindValue(':username', $username, PDO::PARAM_STR);
	$userIdQuery->execute();
	$userId = $userIdQuery->fetch();
	$userIdQuery->closeCursor();
	$first_name = htmlspecialchars($userId['FIRST_NAME']);
	$last_name = htmlspecialchars($userId['LAST_NAME']);
	
	$pwdHashQuery = $database->prepare('
		SELECT
			PWD_HASH
			FROM LOGIN
			WHERE USER_ID = :id;		
		');
	$pwdHashQuery->bindValue(':id', $userId['USER_ID'], PDO::PARAM_STR);
	$pwdHashQuery->execute();
	$pwdHash = $pwdHashQuery->fetch();
	$pwdHashQuery->closeCursor();

	if(password_verify($pwd, $pwdHash['PWD_HASH'])) :
		// log user in and show success message
		session_regenerate_id(true);
		$_SESSION['user_id'] = $userId['USER_ID'];
		$_SESSION['first_name'] = $first_name;
		$_SESSION['last_name'] = $last_name;
?>
  <body>
    <header>
      <div class="loginInfo">
        You are logged in as <a href="myAccount.php"><?=$first_name?></a>. (<a href="index.php">Logout</a>)
      </div>

	  <h1 class="title"><a href="index.php">Keith's Auction Site</a></h1>
    </header>

	<h2 class="pageTitle">Success!</h2>
    <div class="pageContent">
      <p>You successfully logged in as <?=$username?>.</p>

      <form action="myAccount.php">
        <button class="submit" type="submit">View My Account</button>
      </form>
    </div>

<?php
	else:
?>
  <body>
    <header>
	  <h1 class="title"><a href="index.php">Keith's Auction Site</a></h1>
    </header>

	<h2 class="pageTitle">Failure!</h2>
    <div class="pageContent">
      <p>Unable to verify, please try again.</p>

      <form action="login.php">
        <button class="submit" type="submit">Try again</button>
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
