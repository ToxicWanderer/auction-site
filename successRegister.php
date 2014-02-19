<?php
require '/u/ashorn49/openZdatabase.php';
require 'password.php';

$loggedInUserInfoQuery = $database->prepare('
	SELECT
		USERNAME,
		FIRST_NAME,
		LAST_NAME
		FROM PERSON
		WHERE USER_ID = :userId;		
	');
$loggedInUserInfoQuery->bindValue(':userId', 2, PDO::PARAM_INT);
$loggedInUserInfoQuery->execute();
$loggedInUserInfo = $loggedInUserInfoQuery->fetch();
$loggedInUserInfoQuery->closeCursor();
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
        You are logged in as <a href="myAccount.php"><?=htmlspecialchars($loggedInUserInfo['FIRST_NAME'])?></a>. (<a href="index.php">Logout</a>)
      </div>

	  <h1 class="title"><a href="index.php">Keith's Auction Site</a></h1>
    </header>

<?php
$first_name = htmlspecialchars($_POST['first_name']);
$last_name = htmlspecialchars($_POST['last_name']);
$email_1 = htmlspecialchars($_POST['email_1']);
$email_2 = htmlspecialchars($_POST['email_2']);
$username = htmlspecialchars($_POST['username']);
$pwd_1 = htmlspecialchars($_POST['pwd_1']);
$pwd_2 = htmlspecialchars($_POST['pwd_2']);
$accept_terms = htmlspecialchars($_POST['accept_terms']); // 'on'

$emailExistsQuery = $database->prepare('
	SELECT
		USER_ID
		FROM PERSON
		WHERE EMAIL_ADDRESS = :email;		
	');
$emailExistsQuery->bindValue(':email', $email_1, PDO::PARAM_STR);
$emailExistsQuery->execute();
$emailExists = $emailExistsQuery->fetch();
$emailExistsQuery->closeCursor();

$usernameExistsQuery = $database->prepare('
	SELECT
		USER_ID
		FROM PERSON
		WHERE USERNAME = :username;		
	');
$usernameExistsQuery->bindValue(':username', $username, PDO::PARAM_STR);
$usernameExistsQuery->execute();
$usernameExists = $usernameExistsQuery->fetch();
$usernameExistsQuery->closeCursor();

// error checking
if($email_1 !== $email_2) {
?>
    <script>
      alert("The email addresses you entered did not match.\nPlease confirm your email address.");
      history.back();
    </script>
<?php
} elseif($pwd_1 !== $pwd_2) {
?>
    <script>
      alert("The passwords you entered did not match.\nPlease confirm your password.");
      history.back();
    </script>
<?php
} elseif($accept_terms !== 'on') {
?>
    <script>
      alert("You must accept the Terms and Conditions before creating an account.\nPlease accept the Terms and Conditions to continue.");
      history.back();
    </script>
<?php
} elseif(isset($emailExists['USER_ID'])) {
?>
    <script>
      alert("An existing account is already associated with that email address.\nPlease use a new email address or log in to your existing account.");
      history.back();
    </script>
<?php
} elseif(isset($usernameExists['USER_ID'])) {
?>
    <script>
      alert("The username you entered is already claimed by another user.\nPlease choose a new username for your account.");
      history.back();
    </script>
<?php
} elseif(strlen($pwd_1) < 6) {
?>
    <script>
      alert("The password you entered is not long enough.\nPlease choose a password at least 6 characters long.");
      history.back();
    </script>
<?php
} else {

	$newIdQuery = $database->prepare('SELECT NEXT_SEQ_VALUE(:seqGenName);');
	$newIdQuery->bindValue(':seqGenName', 'PERSON', PDO::PARAM_STR);
	$idOk = $newIdQuery->execute();
	$userId = $newIdQuery->fetchColumn(0);
	$newIdQuery->closeCursor();
	
	
	$addUserStmt = $database->prepare('
		INSERT INTO PERSON
			(USER_ID, USERNAME, LAST_NAME, FIRST_NAME, EMAIL_ADDRESS, USER_PHOTO)
			VALUES (:id, :username, :first, :last, :email, NULL);
		');
	$addUserStmt->bindValue(':id', $userId, PDO::PARAM_INT);
	$addUserStmt->bindValue(':username', $username, PDO::PARAM_STR);
	$addUserStmt->bindValue(':first', $first_name, PDO::PARAM_STR);
	$addUserStmt->bindValue(':last', $last_name, PDO::PARAM_STR);
	$addUserStmt->bindValue(':email', $email_1, PDO::PARAM_STR);
	$userAddOk = $addUserStmt->execute();
	$addUserStmt->closeCursor();
	
	$hashedpwd = password_hash($pwd_1, PASSWORD_DEFAULT);
	
	$addLoginStmt = $database->prepare('
		INSERT INTO LOGIN
			(USER_ID, PWD_HASH)
			VALUES (:id, :hash);
		');
	$addLoginStmt->bindValue(':id', $userId, PDO::PARAM_INT);
	$addLoginStmt->bindValue(':hash', $hashedpwd, PDO::PARAM_STR);
	$loginAddOk = $addLoginStmt->execute();
	$addLoginStmt->closeCursor();

	if($idOk and $userAddOk and $loginAddOk) :
		// log user in and show success message
		session_regenerate_id(true);
		$_SESSION['LOGGED_IN_ID'] = $userId;
?>
	<h2 class="pageTitle">Success!</h2>
    <div class="pageContent">
      <p>Congratulations, account successfully created for <?=$first_name.' '.$last_name?>.  What would you like to do now?</p>

      <form action="myAccount.php">
        <button class="submit">View My Account</button>
      </form>
    </div>

<?php
	else:
?>
	<h2 class="pageTitle">Failure!</h2>
    <div class="pageContent">
      <p>Unfortunately we were unable to create your account.  Please try again later.</p>

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
