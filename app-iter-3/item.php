<?php
require '/u/ashorn49/openZdatabase.php';
require 'closeAuction.php';
session_start();

if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') { 
	// we're not running on https
	header('Location: https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	exit (0);
} elseif (!isset($_SESSION['user_id'])) { 
	header('Location: login.php');
	exit (0);
}
$loggedInUserId = $_SESSION['user_id'];

$categoriesQuery = $database->prepare('
	SELECT
		ITEM_CATEGORY_ID,
		NAME
		FROM ITEM_CATEGORY;
	');
$categoriesQuery->execute();
$categories = $categoriesQuery->fetchAll();
$categoriesQuery->closeCursor();

$maxAuctionIdQuery = $database->prepare('
	SELECT
		MAX(AUCTION_ID),
		MIN(AUCTION_ID)
		FROM AUCTION;
	');
$maxAuctionIdQuery->execute();
$maxAuctionId = $maxAuctionIdQuery->fetch();
$maxAuctionIdQuery->closeCursor();

// get status, seller, seller username, item name, description, current bid, [winner,] reserve price, image
$openAuctionQuery = $database->prepare('
    SELECT
        S.NAME AS STATUS,
		P.USERNAME AS SELLER_USERNAME,
		P.USER_ID AS SELLER_ID,
        A.CLOSE_TIME,
        A.ITEM_NAME,
        A.ITEM_DESCRIPTION,
		A.CURRENT_BID_ID,
		A.STARTING_BID AS MIN_BID,
		A.RESERVE_PRICE,
        A.ITEM_PHOTO
        FROM AUCTION A
            JOIN PERSON P ON A.SELLER = P.USER_ID
			JOIN AUCTION_STATUS S ON A.STATUS = S.AUCTION_STATUS_ID
        WHERE A.AUCTION_ID = :auctionId;
    ');
$thisAuctionId = htmlspecialchars($_REQUEST['id']);
if(!isset($thisAuctionId) or empty($thisAuctionId) or $thisAuctionId > $maxAuctionId['MAX(AUCTION_ID)'] or $thisAuctionId < $maxAuctionId['MIN(AUCTION_ID)']){
	header("Location: error.php");
}
$openAuctionQuery->bindValue(':auctionId', $thisAuctionId, PDO::PARAM_INT);
$openAuctionQuery->execute();
$thisAuction = $openAuctionQuery->fetch();
$openAuctionQuery->closeCursor();

$bidQuery = $database->prepare('
	SELECT
		BIDDER,
		AMOUNT
		FROM BID
		WHERE BID_ID = :id;
	');
$bidQuery->bindValue(':id', $thisAuction['CURRENT_BID_ID'], PDO::PARAM_INT);
$bidQuery->execute();
$currentBid = $bidQuery->fetch();
$bidQuery->closeCursor();

$winnerQuery = $database->prepare('
	SELECT
		USERNAME
		FROM PERSON
		WHERE USER_ID = :highBidder;
	');
$winnerQuery->bindValue(':highBidder', $currentBid['BIDDER'], PDO::PARAM_INT);
$winnerQuery->execute();
$winner = $winnerQuery->fetch();
$winnerQuery->closeCursor();
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <title><?=htmlspecialchars($thisAuction['ITEM_NAME'])?></title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <meta charset="utf-8" />
  </head>

  <body>
    <header>
      <div class="loginInfo">
        You are logged in as <a href="myAccount.php"><?=$_SESSION['first_name']?></a>. (<a href="successLogout.php">Logout</a>)
      </div>

	  <h1 class="title"><a href="index.php">Keith's Auction Site</a></h1>

      <form class="search" action="listings.php" method="get">
        <fieldset class="search">
          <legend>Search</legend>
          <select name="category">
            <option value='0'>All Categories</option>
<?php
foreach ($categories as $curr):
?>
            <option value="<?=htmlspecialchars($curr['ITEM_CATEGORY_ID'])?>"><?=htmlspecialchars($curr['NAME'])?></option>
<?php
endforeach;
?>
          </select>
          <input class="search" name="query" type="search" />
          <button class="search" type="submit" formaction="listings.php">Search</button>
        </fieldset>
      </form>
    </header>

	<h2 class="pageTitle">Item Details: <?=htmlspecialchars($thisAuction['ITEM_NAME'])?></h2>

    <div class="pageContent">
      <img class="large" src="itemPhoto.php?id=<?=$thisAuctionId?>" alt="Item Image"/>

<?php
$sellerId = $thisAuction['SELLER_ID'];
$status = htmlspecialchars($thisAuction['STATUS']);
if($loggedInUserId == $sellerId and $status == 'Open'){
      echo '<form name="item_id" method="post"><input type="hidden" name="id" value="'.$thisAuctionId.'" /></form>';
      echo '<h4><a href="#" onclick="document.forms[\'item_id\'].action = \'editItem.php\'; document.forms[\'item_id\'].submit();">Edit this Auction</a> || 
<a href="#" onclick="document.forms[\'item_id\'].action = \'itemRemoved.php\'; document.forms[\'item_id\'].submit();">Cancel Auction</a></h4>';
} elseif ($status =='Open') {
	  echo "<h4>Sold by user: <a href=\"user.php\">".htmlspecialchars($thisAuction['SELLER_USERNAME'])."</a></h4>";

      echo '<form class="floatRight" action="confirmBid.php" method="post">
        <fieldset>
          <legend>Place new bid:</legend>
          $<input type="number" name="bid" required="required" />
          <input type="hidden" name="id" value="'.$thisAuctionId.'"/>
          <button class="submit" type="submit">Place Bid</button>
        </fieldset>
      </form>';
} else {
	  echo "<h4>Sold by user: <a href=\"user.php\">".htmlspecialchars($thisAuction['SELLER_USERNAME'])."</a></h4>";
}
?>

	  <h3>Auction Status: 
<?php 
if($status === 'Open'){
	echo 'Open -- Auction closes at '.htmlspecialchars($thisAuction['CLOSE_TIME']);
} elseif($status === 'Closed' and $sellerId === $loggedInUserId) {
	echo 'Closed -- Auction won by user <a href="user.php">'.htmlspecialchars($winner['USERNAME']).'</a>';
} elseif($status === 'Closed' and $currentBid['BIDDER'] === $loggedInUserId) {
	echo 'Closed -- Congratulation, You\'ve Won! <a href="pay.php">Click to pay for item</a>';
} elseif($status === 'Closed' and $currentBid['BIDDER'] !== $loggedInUserId) {
	echo 'Closed -- Better Luck Next Time!';
} elseif($status === 'Failed') {
	echo 'Auction Failed -- No Bids were Placed';
} else {
	echo $status;
}
?> </h3>

      <h4 class="price">
<?php
if(isset($currentBid['AMOUNT'])){
	echo 'Current Bid: $'.htmlspecialchars($currentBid['AMOUNT']);
} elseif($loggedInUserId == $thisAuction['SELLER_ID']) {
	echo 'No bids have been placed yet';
} elseif($loggedInUserId != $thisAuction['SELLER_ID']) {
	echo 'Place the first bid!  Minimum: $'.htmlspecialchars($thisAuction['MIN_BID']);
}
?>
      </h4>
<?php
if($thisAuction['RESERVE_PRICE'] != '0.00') {
      echo '<h5 class="price">Reserve this item for: $'.htmlspecialchars($thisAuction['RESERVE_PRICE']).'</h5>';
}
?>
      <p><?=htmlspecialchars($thisAuction['ITEM_DESCRIPTION'])?></p>
    </div>

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
