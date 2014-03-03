<?php
require '/u/ashorn49/openZdatabase.php';
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

$conditionsQuery = $database->prepare('
	SELECT
		ITEM_CONDITION_ID,
		NAME
		FROM ITEM_CONDITION;
	');
$conditionsQuery->execute();
$conditions = $conditionsQuery->fetchAll();
$conditionsQuery->closeCursor();
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
		A.AUCTION_ID,
		A.SELLER,
        A.CLOSE_TIME,
        A.ITEM_NAME,
        A.ITEM_DESCRIPTION,
		A.ITEM_CONDITION,
		A.ITEM_CATEGORY,
		C.NAME AS CONDITION_NAME,
		A.CURRENT_BID_ID,
		A.STARTING_BID AS MIN_BID,
		A.RESERVE_PRICE,
        A.ITEM_PHOTO
        FROM AUCTION A
			JOIN ITEM_CONDITION AS C ON A.ITEM_CONDITION = C.ITEM_CONDITION_ID
        WHERE AUCTION_ID = :auctionId;
    ');
$thisAuctionId = htmlspecialchars($_POST['id']);
if(!isset($thisAuctionId) or empty($thisAuctionId) or $thisAuctionId > $maxAuctionId['MAX(AUCTION_ID)'] or $thisAuctionId < $maxAuctionId['MIN(AUCTION_ID)']){
	header("Location: error.php");
}
$openAuctionQuery->bindValue(':auctionId', $thisAuctionId, PDO::PARAM_INT);
$openAuctionQuery->execute();
$thisAuction = $openAuctionQuery->fetch();
$openAuctionQuery->closeCursor();

if ($_SESSION['user_id'] != $thisAuction['SELLER']) { 
	header('Location: login.php');
	exit (0);
}

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
?>
	<h2 class="pageTitle">Edit Listing</h2>

    <div class="pageContent">
      <form action="successEdit.php" method="post" enctype="multipart/form-data">
        <fieldset>
          <legend>Auction Details</legend>
          <dl>
            <input type="hidden" name="id" value="<?=$thisAuctionId?>">
            <dt>Item Name:</dt>
            <dd><input type="text" name="name" value="<?=htmlspecialchars($thisAuction['ITEM_NAME'])?>" required="required" /></dd>
<?php
if(!isset($currentBid['BIDDER'])):
?>
            <dt>Starting Bid:</dt>
            <dd>$<input type="number" name="starting_bid" value="<?=htmlspecialchars($thisAuction['MIN_BID'])?>" required="required" /></dd>
<?php
else:
?>
            <input type="hidden" name="starting_bid" value="<?=htmlspecialchars($thisAuction['MIN_BID'])?>" required="required" /></dd>
<?php
endif;
?>
            <dt>Reserve Price:</dt>
            <dd>$<input type="number" name="reserve_bid" value="<?=htmlspecialchars($thisAuction['RESERVE_PRICE'])?>" /><br>&nbsp;&nbsp;&nbsp;* a value of $0.00 denotes that there is no reserve price</dd>
            <dt>Category:</dt>
            <dd>
              <select name="category">
<?php
foreach ($categories as $curr):
?>
                <option value="<?=htmlspecialchars($curr['ITEM_CATEGORY_ID'])?>" <?=$thisAuction['ITEM_CATEGORY'] == $curr['ITEM_CATEGORY_ID'] ? ' selected="selected"' : '';?>><?=htmlspecialchars($curr['NAME'])?></option>
<?php
endforeach;
?>
              </select>
            </dd>
            <dt>Item Description:</dt>
            <dd><textarea name="description" required="required"><?=htmlspecialchars($thisAuction['ITEM_DESCRIPTION'])?></textarea></dd>
            <dt>Condition:</dt>
            <dd>
              <select name="condition">
<?php
foreach ($conditions as $curr):
?>
                <option value="<?=htmlspecialchars($curr['ITEM_CONDITION_ID'])?>" <?=$thisAuction['ITEM_CONDITION'] == $curr['ITEM_CONDITION_ID'] ? ' selected="selected"' : '';?>><?=htmlspecialchars($curr['NAME'])?></option>
<?php
endforeach;
?>
              </select>
            </dd>
            <dt>Close Auction At:</dt>
            <dd>
              <select name="hour">
<?php
for ($i = 1; $i <= 12; $i++):
?>
                <option <?=$i == date('h', strtotime($thisAuction['CLOSE_TIME'])) ? ' selected="selected"' : '';?>><?=sprintf('%02d', $i)?></option>
<?php
endfor;
?>
              </select>:
              <select name="minute">
<?php
for ($i = 0; $i < 60; $i+=5):
?>
                <option <?=$i == date('i', strtotime($thisAuction['CLOSE_TIME'])) ? ' selected="selected"' : '';?>><?=sprintf('%02d', $i)?></option>
<?php
endfor;
?>
              </select>
              <select name="am_pm">
                <option <?='am' == date('a', strtotime($thisAuction['CLOSE_TIME'])) ? ' selected="selected"' : '';?>>am</option>
                <option <?='pm' == date('a', strtotime($thisAuction['CLOSE_TIME'])) ? ' selected="selected"' : '';?>>pm</option>
              </select>
              <label>on</label>
              <select name="month">
<?php
for ($i = 1; $i <= 12; $i++) :
?>
                <option value="<?=sprintf('%02d', $i)?>" <?=$i == date('n', strtotime($thisAuction['CLOSE_TIME'])) ? ' selected="selected"' : '';?>><?=date("F", mktime(0,0,0,$i+1,0,0,0))?></option>
<?php
endfor;
?>
              </select>
              <select name="day">
<?php
for ($i = 1; $i <= 31; $i++):
?>
                <option <?=$i == date('j', strtotime($thisAuction['CLOSE_TIME'])) ? ' selected="selected"' : '';?>><?=sprintf('%02d', $i)?></option>
<?php
endfor;
?>
              </select>
            </dd>
            <dt>Upload New Photo:</dt>
            <dd><input type="file" accept="image/*" name="photo"/></dd>
          </dl>
          <div class="center">
            <button class="submit">Submit</button>
            <button class="cancel" formaction="myAccount.php" formnovalidate="formnovalidate">Cancel</button>
          </div>
        </fieldset>
      </form>
    </div>

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
