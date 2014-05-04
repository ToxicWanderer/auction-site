<?php
require 'db.php';
session_start();

if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') { 
	// we're not running on https
	header('Location: https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	exit (0);
} elseif (!isset($_SESSION['user_id'])) { 
	header('Location: login.php');
	exit (0);
}

$auctionId = htmlspecialchars($_POST['id']);

$photoQuery = $database->prepare('
	SELECT
		ITEM_PHOTO
		FROM AUCTION
		WHERE AUCTION_ID = :auctionId;
	');
$photoQuery->bindValue(':auctionId', $auctionId, PDO::PARAM_INT);
$photoQuery->execute();
$photo = $photoQuery->fetch();
$photoQuery->closeCursor();

$closeTimeQuery = $database->prepare('
	SELECT
		CLOSE_TIME
		FROM AUCTION
		WHERE AUCTION_ID = :id;
	');
$closeTimeQuery->bindValue(':id', htmlspecialchars($_POST['id']), PDO::PARAM_STR);
$closeTimeQuery->execute();
$closeTime = $closeTimeQuery->fetch();
$closeTimeQuery->closeCursor();
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

if(!is_numeric(htmlspecialchars($_POST['starting_bid']))) {
?>
    <script>
      alert("You entered a non-numeric value as the starting bid.\nBids must have a numeric value.");
      history.back();
    </script>
<?php
} elseif($_POST['reserve_bid'] != "" and !is_numeric(htmlspecialchars($_POST['reserve_bid']))) {
?>
    <script>
      alert("You entered a non-numeric value as the reserve price.\nBids must have a numeric value.");
      history.back();
    </script>
<?php
} elseif(htmlspecialchars($_POST['category']) == 0) {
?>
    <script>
      alert("You did not select a category for you item.\nAll items must have a category.");
      history.back();
    </script>
<?php
} elseif(time() > strtotime(htmlspecialchars($closeTime['CLOSE_TIME']))) {
?>
    <script>
      alert("This auction is already closed.\nYou cannot edit an auction that has closed.");
      history.back();
    </script>
<?php
} else {
	$updateAuctionStmt = $database->prepare('
	    UPDATE AUCTION
			SET STARTING_BID = :startingBid,
				RESERVE_PRICE = :reservePrice,
				CLOSE_TIME = :closeTime,
				ITEM_CATEGORY = :category,
				ITEM_NAME = :name,
				ITEM_DESCRIPTION = :description,
				ITEM_CONDITION = :condition,
				ITEM_PHOTO = :photo
			WHERE AUCTION_ID = :auctionId;
	    ');
	
	$updateAuctionStmt->bindValue(':auctionId', $auctionId, PDO::PARAM_INT);
	$updateAuctionStmt->bindValue(':startingBid', htmlspecialchars($_POST['starting_bid']), PDO::PARAM_STR);
	$updateAuctionStmt->bindValue(':reservePrice', htmlspecialchars($_POST['reserve_bid']), PDO::PARAM_STR);
	$date = htmlspecialchars($_POST['date']);
	$hour = $_POST['hour'];
	if($hour % 12 == 0){
		$hour -= 12;
	}
	if($_POST['am_pm'] == 'pm'){
		$hour += 12;
	}
	$minute = htmlspecialchars($_POST['minute']);
	$timeString = $date.' '.$hour.':'.$minute.':00';
	$updateAuctionStmt->bindValue(':closeTime', $timeString, PDO::PARAM_STR);
	$updateAuctionStmt->bindValue(':category', $_POST['category'], PDO::PARAM_INT);
	$updateAuctionStmt->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
	$updateAuctionStmt->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
	$updateAuctionStmt->bindValue(':condition', $_POST['condition'], PDO::PARAM_INT);
	if(isset($_FILES['photo']) and $_FILES['photo']['error'] === 0){
		$photoFile = fopen($_FILES['photo']['tmp_name'], 'rb');
		$updateAuctionStmt->bindValue(':photo', $photoFile, PDO::PARAM_LOB);
	} else {
		$updateAuctionStmt->bindValue(':photo', $photo['ITEM_PHOTO'], PDO::PARAM_LOB);
	}
	$ok = $updateAuctionStmt->execute();
	$updateAuctionStmt->closeCursor();
}
?>
<?php
if($ok) :
?> 
	<h2 class="pageTitle">Success!</h2>
    <div class="pageContent">
      <p> Action successfully completed </p>
      <form>
        <button class="submit" type="submit" formaction="myAccount.php">Accept</button>
      </form>
    </div>
<?php
else :
?>
	<h2 class="pageTitle">Failure!</h2>
    <div class="pageContent">
      <p> An error occurred: edit could not be made </p>
      <form>
        <button class="submit" type="submit" formaction="myAccount.php">Accept</button>
      </form>
    </div>
<?php
endif;
?>

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
