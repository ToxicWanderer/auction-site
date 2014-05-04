<?php
require 'db.php';
require 'closeAuction.php';
session_start();

if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') { 
	// we're not running on https
	header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
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

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <title>Keith's Auction Site: Listings</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <meta charset="utf-8" />
    <script src="utils.js"></script>
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
            <option value="0">All Categories</option>
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
<!--          <select>
            <option>Item Name</option>
            <option>Seller Name</option>
          </select>
          <button class="search" type="submit" formaction="listings.php">Search</button>
          <div class="filterOrder">
            <input type="checkbox" />
            <label>Filter by price</label>
            <select>
              <option>Less than</option>
              <option>Greater than</option>
            </select>
            <label>   $</label>
            <input type="number" />
            <br />
            <input type="checkbox" />
            <label>Order By</label>
            <select>
              <option>Relevance</option>
              <option>Price: Low to High</option>
              <option>Price: High to Low</option>
              <option>Closing Soon</option>
            </select>
          </div>-->
        </fieldset>
      </form>
    
    </header>

	<h2 class="pageTitle">Item Listings</h2>
    <br/>

    <div class="pageContent">

<?php
$auctionsQuery = $database->prepare('
    SELECT
		AUCTION_ID,
		SELLER AS SELLER_ID,
        ITEM_NAME,
        ITEM_DESCRIPTION,
		CURRENT_BID_ID,
		STARTING_BID AS MIN_BID,
		RESERVE_PRICE,
        ITEM_PHOTO
        FROM AUCTION
        WHERE STATUS = 1 AND (ITEM_NAME LIKE :q OR ITEM_DESCRIPTION LIKE :q) AND ITEM_CATEGORY LIKE :category
		ORDER BY AUCTION_ID DESC;
    ');
$query = htmlspecialchars($_GET['query']);
$category = htmlspecialchars($_GET['category']);
$auctionsQuery->bindValue(':q', "%$query%", PDO::PARAM_STR);
$auctionsQuery->bindValue(':category', ($category) ?: "%", PDO::PARAM_STR);
$auctionsQuery->execute();
$auctions = $auctionsQuery->fetchAll();
$auctionsQuery->closeCursor();

foreach ($auctions as $auction):
$thisAuctionId = htmlspecialchars($auction['AUCTION_ID']);
?>
      <div class="listings">
        <a href="item.php?id=<?=$thisAuctionId?>"><img src="itemPhoto.php?id=<?=$thisAuctionId?>" class="floatLeft" alt="Item Image"/></a>
        <h4><a href="item.php?id=<?=$thisAuctionId?>"><?=htmlspecialchars($auction['ITEM_NAME'])?></a> -- 
<?php

if(isset($auction['CURRENT_BID_ID'])){
	$bidQuery = $database->prepare('
		SELECT
			BIDDER,
			AMOUNT
			FROM BID
			WHERE BID_ID = :id;
		');
	$bidQuery->bindValue(':id', $auction['CURRENT_BID_ID'], PDO::PARAM_INT);
	$bidQuery->execute();
	$currentBid = $bidQuery->fetch();
	$bidQuery->closeCursor();
	echo 'Current bid: $'.htmlspecialchars($currentBid['AMOUNT']);
} elseif($loggedInUserId == $auction['SELLER_ID']) {
	echo 'No bids have been placed yet';
} elseif($loggedInUserId != $auction['SELLER_ID']) {
	echo 'Place the first bid!  Minimum: $'.htmlspecialchars($auction['MIN_BID']);
}
?>
        </h4>
<?php 
if($loggedInUserId != $auction['SELLER_ID'] and $auction['RESERVE_PRICE'] != '0.00') {
        echo '<h5>Reserve this item for $'.htmlspecialchars($auction['RESERVE_PRICE']).'</h5>';
}

$descId = "desc".$thisAuctionId;
$toggleId = "toggle".$thisAuctionId;
?>

        <p class="hidden" id="<?=$descId?>"><?=htmlspecialchars($auction['ITEM_DESCRIPTION'])?></p>
        <p><a id="<?=$toggleId?>" onclick="toggleDesc('<?=$descId?>', '<?=$toggleId?>');" href="javascript:void(0);">-- Show Full Description --</a></p>

      </div>

<?php
endforeach;
?>
      <hr />

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
