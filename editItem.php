<?php
require 'httpsRedirect.php';
require 'db.php';
session_start();

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
    <script src="//code.jquery.com/jquery-1.9.1.js"></script>
    <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css" />
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <script src="utils.js"></script>
    <script src="validateAddForm.js"></script>
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
      <form action="successEdit.php" method="post" id="main_form" onsubmit="return checkAddForm();" enctype="multipart/form-data">
        <fieldset>
          <legend>Auction Details</legend>
          <dl>
            <input type="hidden" name="id" value="<?=$thisAuctionId?>">
            <dt>Item Name:</dt>
            <dd>
              <input type="text" name="name" id="name" value="<?=htmlspecialchars($thisAuction['ITEM_NAME'])?>" onblur="checkName()" />
              <span class="error_msg" id="name_err"></span>
            </dd>
<?php
if(!isset($currentBid['BIDDER'])):
?>
            <dt>Starting Bid:</dt>
            <dd>
              $<input type="number" name="starting_bid" id="start_bid" value="<?=htmlspecialchars($thisAuction['MIN_BID'])?>" onblur="checkStartBid()" />
              <span class="error_msg" id="start_bid_err"></span>
            </dd>
<?php
else:
?>
            <input type="hidden" name="starting_bid" value="<?=htmlspecialchars($thisAuction['MIN_BID'])?>" /></dd>
<?php
endif;
?>
            <dt>Reserve Price:</dt>
            <dd>
              $<input type="number" name="reserve_bid" id="reserve" value="<?=htmlspecialchars($thisAuction['RESERVE_PRICE'])?>" onblur="checkReserve()"/>
              <span class="error_msg" id="reserve_err"></span>
            </dd>
            <dd>
              &nbsp;&nbsp;&nbsp;* a value of $0.00 denotes that there is no reserve price
            </dd>

            <dt>Category:</dt>
            <dd>
              <select name="category" id="category">
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
            <dd>
              <textarea name="description" id="desc" onblur="checkDesc()"><?=htmlspecialchars($thisAuction['ITEM_DESCRIPTION'])?></textarea>
              <div class="error_msg" id="desc_err"></div>
            </dd>
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
              <script>
                $(function() { $( "#datepicker" ).datepicker({ 
		      		dateFormat: "yy-mm-dd",
					minDate: 0,
					maxDate: "+2M",
                    defaultDate: new Date(<?=$thisAuction['CloseTime']?>),
		      		showButtonPanel:true }); });
              </script>
              <input type="text" id="datepicker" name="date" value="<?=date("Y-m-d", strtotime($thisAuction['CLOSE_TIME']))?>" readonly="readonly"/>
            </dd>
            <dt>Upload New Photo:</dt>
            <dd><input type="file" accept="image/*" name="photo"/></dd>
          </dl>
          <div class="error_msg center" id="submit_err"></div>
          <div class="center">
            <button class="submit" type="submit">Submit</button>
            <button class="cancel" type="button" onclick="history.back()">Cancel</button>
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
