<?php
require 'db.php';
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
// ADDRESS_ID, USER_ID, 1=Billing, NAME, STREET_1, STREET_2, CITY, STATE, ZIP
// ADDRESS_ID, USER_ID, 2=Shipping, NAME, STREET_1, STREET_2, CITY, STATE, ZIP
// BILLING_INFO_ID, USER_ID, LAST_NAME, FIRST_NAME, CARD_TYPE, CARD_NUMBER, EXP_MONTH, EXP_DAY, SECURITY_CODE

$selectedItems = $_POST['items'];
foreach($selectedItems as $curr) {
	$updateAuctionStmt = $database->prepare('
	    UPDATE AUCTION
			SET PAID = 1
			WHERE AUCTION_ID = :id;
	    ');
	
	$updateAuctionStmt->bindValue(':id', $curr, PDO::PARAM_INT);
	$updateAuctionStmt->execute();
	$updateAuctionStmt->closeCursor();
}
?>
	<h2 class="pageTitle">Success!</h2>
    <div class="pageContent">
      <p>Payment successfully processed!</p>
      <p>Note: Not really.  This isn't an actually ecommerce site.</p>

      <form action="myAccount.php">
        <button class="submit" type="submit">Return to My Account</button>
      </form>
    </div>

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
