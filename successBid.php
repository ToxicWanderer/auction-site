<?php
require '/u/ashorn49/openZdatabase.php';

$loggedInUserInfoQuery = $database->prepare('
	SELECT
		USER_ID,
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
$currBidQuery = $database->prepare('
	SELECT
		B.AMOUNT
		FROM BID B 
		JOIN AUCTION A ON A.CURRENT_BID_ID = B.BID_ID
		WHERE A.AUCTION_ID = :id;
	');
$currBidQuery->bindValue(':id', htmlspecialchars($_POST['id']), PDO::PARAM_INT);
$currBidQuery->execute();
$currBid = $currBidQuery->fetch();
$currBidQuery->closeCursor();

$bidAmount = htmlspecialchars($_POST['bid']);
if(!is_numeric($bidAmount) or htmlspecialchars($currBid['AMOUNT']) >= $bidAmount){
	header("Location: error.php");
} else {
	$newIdQuery = $database->prepare('SELECT NEXT_SEQ_VALUE(:seqGenName);');
	$newIdQuery->bindValue(':seqGenName', 'BID', PDO::PARAM_STR);
	$newIdQuery->execute();
	$bidId = htmlspecialchars($newIdQuery->fetchColumn(0));
	$newIdQuery->closeCursor();
	
	$addBidQuery = $database->prepare('
		INSERT INTO BID
			(BID_ID, BIDDER, AUCTION, BID_TIME, AMOUNT)
			VALUES (:id, :bidder, :auctionId, CURRENT_TIMESTAMP, :amount);
		');
	$addBidQuery->bindValue(':id', $bidId, PDO::PARAM_INT);
	$addBidQuery->bindValue(':bidder', htmlspecialchars($loggedInUserInfo['USER_ID']), PDO::PARAM_STR);
	$addBidQuery->bindValue(':auctionId', htmlspecialchars($_POST['id']), PDO::PARAM_INT);
	$addBidQuery->bindValue(':amount', htmlspecialchars($_POST['bid']), PDO::PARAM_STR);
	$bidOk = $addBidQuery->execute();
	$addBidQuery->closeCursor();
	
	$updateAuctionQuery = $database->prepare('
		UPDATE AUCTION
			SET CURRENT_BID_ID = :newBid
			WHERE AUCTION_ID = :id;
		');
	$updateAuctionQuery->bindValue(':newBid', $bidId, PDO::PARAM_INT);
	$updateAuctionQuery->bindValue(':id', htmlspecialchars($_POST['id']), PDO::PARAM_STR);
	$auctionOk = $updateAuctionQuery->execute();
	$updateAuctionQuery->closeCursor();

	if($auctionOk and $bidOk) :
?>
	<h2 class="pageTitle">Success!</h2>
    <div class="pageContent">
      <p> Bid successfully placed </p>

      <form action="listings.php">
        <button class="submit">Accept</button>
      </form>
    </div>

<?php
	else :
?>
	<h2 class="pageTitle">Failure!</h2>
    <div class="pageContent">
      <p> An error occurred: unable to place bid </p>

      <form action="listings.php">
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
