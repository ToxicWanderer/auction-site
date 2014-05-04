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
} else {
	$newIdQuery = $database->prepare('SELECT NEXT_SEQ_VALUE(:seqGenName);');
	$newIdQuery->bindValue(':seqGenName', 'AUCTION', PDO::PARAM_STR);
	$newIdQuery->execute();
	$auctionId = $newIdQuery->fetchColumn(0);
	$newIdQuery->closeCursor();

	$insertAuctionStmt = $database->prepare('
	    INSERT INTO AUCTION
		(AUCTION_ID, STATUS, SELLER, CURRENT_BID_ID, STARTING_BID, RESERVE_PRICE, OPEN_TIME, CLOSE_TIME, ITEM_CATEGORY, ITEM_NAME, ITEM_DESCRIPTION, ITEM_CONDITION, ITEM_PHOTO)
	        VALUES (:auctionId, :status, :seller, NULL, :startingBid, :reservePrice, CURRENT_TIMESTAMP, :closeTime, :category, :name, :description, :condition, :photo);
	    ');
	
	$insertAuctionStmt->bindValue(':auctionId', $auctionId, PDO::PARAM_INT);
	$insertAuctionStmt->bindValue(':status', 1, PDO::PARAM_INT);
	$insertAuctionStmt->bindValue(':seller', $_SESSION['user_id'], PDO::PARAM_INT);
	$insertAuctionStmt->bindValue(':startingBid', htmlspecialchars($_POST['starting_bid']), PDO::PARAM_STR);
	$insertAuctionStmt->bindValue(':reservePrice', htmlspecialchars($_POST['reserve_bid']), PDO::PARAM_STR);
	$date = htmlspecialchars($_POST['date']);
	$hour = $_POST['hour'];
	if($_POST['am_pm'] == 'pm'){
		$hour += 12;
	}
	$minute = htmlspecialchars($_POST['minute']);
	$timeString = $date.' '.$hour.':'.$minute.':00';
	$insertAuctionStmt->bindValue(':closeTime', $timeString, PDO::PARAM_STR);
	$insertAuctionStmt->bindValue(':category', $_POST['category'], PDO::PARAM_INT);
	$insertAuctionStmt->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
	$insertAuctionStmt->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
	$insertAuctionStmt->bindValue(':condition', $_POST['condition'], PDO::PARAM_INT);
	if(isset($_FILES['photo']) and $_FILES['photo']['error'] === 0){
		$photoFile = fopen($_FILES['photo']['tmp_name'], 'rb');
		$insertAuctionStmt->bindValue(':photo', $photoFile, PDO::PARAM_LOB);
	} else {
		$insertAuctionStmt->bindValue(':photo', NULL, PDO::PARAM_NULL);
	}
	$ok = $insertAuctionStmt->execute();
	$insertAuctionStmt->closeCursor();
}
if($ok) :
?>
	<h2 class="pageTitle">Success!</h2>
    <div class="pageContent">
      <p> Item successfully added </p>
      <form>
        <button class="submit" type="submit" formaction="myAccount.php">Accept</button>
      </form>
    </div>

<?php
	else :
?>
	<h2 class="pageTitle">Failure!</h2>
    <div class="pageContent">
      <p> An error occurred: unable to add item </p>
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
