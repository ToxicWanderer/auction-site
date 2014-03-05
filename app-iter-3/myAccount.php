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

    <img class="medium" src="notFound.jpg" alt="User Profile Image"/>

	<h2 class="pageTitle">My Account -- <?=$_SESSION['first_name']." ".$_SESSION['last_name']?></h2>

    <div class="pageContent">
      <h4><a href="addItem.php">Put a New Item up for Auction</a> || <a href="listings.php">View Listings</a></h4>

      <br />

<?php
$unpaidAuctionsQuery = $database->prepare('
	SELECT
		A.AUCTION_ID,
		A.ITEM_NAME,
		A.CLOSE_TIME,
		B.AMOUNT
		FROM AUCTION AS A 
			JOIN BID AS B 
			ON A.CURRENT_BID_ID = B.BID_ID
		WHERE A.STATUS = 3
			AND B.BIDDER = :id
			AND (A.PAID = 0 OR A.PAID IS NULL);
	');
$unpaidAuctionsQuery->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$unpaidAuctionsQuery->execute();
$unpaidAuctions = $unpaidAuctionsQuery->fetchAll();
$unpaidAuctionsQuery->closeCursor();

if(isset($unpaidAuctions[0])){
?>
      <h3>Unpaid Auctions</h3>
      <form action="pay.php" method="post">
        <table>
          <thead>
            <tr>
              <th>Item ID.</th>
              <th>Item Name</th>
              <th>Date Closed</th>
              <th>Price</th>
              <th>Pay</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td><button type="submit" class="search">Pay</button></td>
            </tr>
          </tfoot>
          <tbody>
<?php
	foreach ($unpaidAuctions as $curr){
		$currId = $curr['AUCTION_ID'];
?>
            <tr>
              <td><a href="item.php?id=<?=$currId?>"><?=$currId?></a></td>
              <td><a href="item.php?id=<?=$currId?>"><?=$curr['ITEM_NAME']?></a></td>
              <td><?=$curr['CLOSE_TIME']?></td>
              <td>$<?=$curr['AMOUNT']?></td>
              <td><input type="checkbox" name="items[]" value="<?=$currId?>" /></td>
            </tr>
<?php
	}
?>
          </tbody>
        </table>    
      </form>
<?php
}
?>

      <h3>Watched Items: (feature coming soon!)</h3>
      <table>
        <thead>
          <tr>
            <th>Item ID.</th>
            <th>Item Name</th>
            <th>Close Time</th>
            <th>Current Price</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>[<a href="item.php">number</a>]</td>
            <td>[<a href="item.php">name</a>]</td>
            <td>[date]</td>
            <td>[price]</td>
            <td>[Winning/Place A Bid/Won/Better Luck Next Time]</td>
          </tr>
        </tbody>
      </table>    

      <h3>My Auctions</h3>
      <table>
        <thead>
          <tr>
            <th>Item ID.</th>
            <th>Item Name</th>
            <th>Close Time</th>
            <th>Current Price</th>
            <th>Status</th>
            <th class="overScrollBar"></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="6" class="colspan">
              <div class="scrollingTable">
                <table>
<?php
$myAuctionsQuery = $database->prepare('
	SELECT
		A.AUCTION_ID,
		A.ITEM_NAME,
		A.CLOSE_TIME,
		A.CURRENT_BID_ID,
		S.NAME AS STATUS
		FROM AUCTION A
			JOIN AUCTION_STATUS S ON A.STATUS = S.AUCTION_STATUS_ID
		WHERE A.SELLER = :seller
		ORDER BY AUCTION_ID DESC;
	');
$myAuctionsQuery->bindValue(':seller', $_SESSION['user_id'], PDO::PARAM_INT);
$myAuctionsQuery->execute();
$myAuctions = $myAuctionsQuery->fetchAll();
$myAuctionsQuery->closeCursor();


foreach ($myAuctions as $auction) :
?>
                  <tr>
                    <td><a href="item.php?id=<?=htmlspecialchars($auction['AUCTION_ID'])?>"><?=htmlspecialchars($auction['AUCTION_ID'])?></a></td>
                    <td><a href="item.php?id=<?=htmlspecialchars($auction['AUCTION_ID'])?>"><?=htmlspecialchars($auction['ITEM_NAME'])?></a></td>
                    <td><?=htmlspecialchars($auction['CLOSE_TIME'])?></td>
<?php
$bidsQuery = $database->prepare('
	SELECT
		B.AMOUNT
		FROM AUCTION A
			JOIN BID B ON A.CURRENT_BID_ID = B.BID_ID
		WHERE A.CURRENT_BID_ID = :id;
	');
$bidsQuery->bindValue(':id', $auction['CURRENT_BID_ID'], PDO::PARAM_INT);
$bidsQuery->execute();
$bids = $bidsQuery->fetch();
$bidsQuery->closeCursor();
?>

                    <td>
<?php
if(isset($bids['AMOUNT'])){
	echo '$ '.htmlspecialchars($bids['AMOUNT']);
} else {
	echo 'No bids yet';
}
?>
                    </td>
                    <td><?=htmlspecialchars($auction['STATUS'])?></td>
                  </tr>
<?php
endforeach;
?>
                </table>
              </div>
            </td>
          </tr>
        </tbody>
      </table>    
    </div>

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
