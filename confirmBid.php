<?php
require 'httpsRedirect.php';
require 'db.php';
session_start();

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
if(!is_numeric(htmlspecialchars($_POST['bid']))) :
?>
    <script>
      alert("You entered a non-numeric value as your bid.\nBids must have a numeric value.");
      window.location="item.php?id=<?=htmlspecialchars($_POST['id'])?>";
    </script>
<?php
elseif(htmlspecialchars($_POST['bid']) <= $currBid['AMOUNT']):
?>
    <script>
      alert("To place a bid, your bid must be higher than the current winning bid.");
      window.location="item.php?id=<?=htmlspecialchars($_POST['id'])?>";
    </script>
<?php
elseif(time() > strtotime(htmlspecialchars($closeTime['CLOSE_TIME']))): 
?>
    <script>
      alert("This auction is already closed.\nYou cannot bid on an auction that has closed.");
      history.back();
    </script>
<?php
else :
?>
	<h2 class="pageTitle">Confirm</h2>

    <div class="pageContent">
      <p>Are you sure you would like to place this bid of $<?=htmlspecialchars($_POST['bid'])?>?</p>

      <form action="successBid.php" method="post">
        <input type="hidden" name="id" value="<?=htmlspecialchars($_POST['id'])?>"/>
        <input type="hidden" name="bid" value="<?=htmlspecialchars($_POST['bid'])?>"/>
        <button class="cancel" type="button" onclick="history.back()">No</button>
        <button class="submit" type="submit">Yes</button>
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
