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

	<h2 class="pageTitle">Confirm</h2>

    <div class="pageContent">
      <p>Please confirm your order as well as your payment and shipping information.</p>

      <h3>Items</h3>
      <table>
        <thead>
          <tr>
            <th>Item ID.</th>
            <th>Item Name</th>
            <th>Date</th>
            <th>Current Price</th>
          </tr>
        </thead>
        <tbody>
<?php
$selectedItems = $_POST['items'];
$totalPrice = 0;
foreach($selectedItems as $curr) {

	$itemInfoQuery = $database->prepare('
		SELECT
			A.ITEM_NAME,
			A.CLOSE_TIME,
			B.AMOUNT
			FROM AUCTION AS A 
				JOIN BID AS B 
				ON A.CURRENT_BID_ID = B.BID_ID
			WHERE A.AUCTION_ID = :id;
		');
	$itemInfoQuery->bindValue(':id', $curr, PDO::PARAM_INT);
	$itemInfoQuery->execute();
	$itemInfo = $itemInfoQuery->fetch();
	$itemInfoQuery->closeCursor();

	$totalPrice += $itemInfo['AMOUNT'];
?>
          <tr>
            <td><?=$curr?></td>
            <td><?=$itemInfo['ITEM_NAME']?></td>
            <td><?=$itemInfo['CLOSE_TIME']?></td>
            <td>$<?=$itemInfo['AMOUNT']?></td>
          </tr>
<?php
}
?>
          <tr>
            <td></td>
            <td></td>
            <td>Total:</td>
            <td>$<?=$totalPrice?></td>
          </tr>
        </tbody>
      </table>    

      <div class="floatLeft marginMedium">
        <h3>Billing Info</h3>
        <dl>
          <dt>Name: </dt>
          <dd><?=htmlspecialchars($_POST['pay_first_name']).' '.htmlspecialchars($_POST['pay_last_name'])?></dd>
          <dt>Type: </dt>
          <dd><?=htmlspecialchars($_POST['pay_card_type'])?></dd>
          <dt>Number:</dt>
          <dd><?=htmlspecialchars($_POST['pay_card_no'])?></dd>
          <dt>Exp Mo:</dt>
          <dd><?=htmlspecialchars($_POST['pay_exp_mo'])?></dd>
          <dt>Exp Day</dt>
          <dd><?=htmlspecialchars($_POST['pay_exp_day'])?></dd>
        </dl>
      </div>

      <div class="floatLeft marginMedium">
        <h3>Billing Address</h3>
        <dl>
          <dt>Name: </dt>
          <dd><?=htmlspecialchars($_POST['bill_name'])?></dd>
          <dt>Street 1: </dt>
          <dd><?=htmlspecialchars($_POST['bill_addr_1'])?></dd>
          <dt>Street 2: </dt>
          <dd><?=htmlspecialchars($_POST['bill_addr_2'])?></dd>
          <dt>Ciy:</dt>
          <dd><?=htmlspecialchars($_POST['bill_city'])?></dd>
          <dt>State:</dt>
          <dd><?=htmlspecialchars($_POST['bill_state'])?></dd>
          <dt>Zip</dt>
          <dd><?=htmlspecialchars($_POST['bill_zip'])?></dd>
        </dl>
      </div>

      <div class="floatLeft marginMedium">
        <h3>Shipping Address</h3>
        <dl>
          <dt>Name: </dt>
          <dd><?=htmlspecialchars($_POST['ship_name'])?></dd>
          <dt>Street 1: </dt>
          <dd><?=htmlspecialchars($_POST['ship_addr_1'])?></dd>
          <dt>Street 2: </dt>
          <dd><?=htmlspecialchars($_POST['ship_addr_2'])?></dd>
          <dt>Ciy:</dt>
          <dd><?=htmlspecialchars($_POST['ship_city'])?></dd>
          <dt>State:</dt>
          <dd><?=htmlspecialchars($_POST['ship_state'])?></dd>
          <dt>Zip</dt>
          <dd><?=htmlspecialchars($_POST['ship_zip'])?></dd>
        </dl>
      </div>

      <form class="clear" action="successPay.php" method="post">
<?php
$selectedItems = $_POST['items'];
foreach($selectedItems as $curr) {
?>
        <input type="hidden" name="items[]" value="<?=$curr?>" /> 
<?php
}
?>
        <button class="cancel" formaction="myAccount.php" formnovalidate="formnovalidate">Cancel</button>
        <button class="cancel" formaction="pay.php" formnovalidate="formnovalidate">Incorrect Information</button>
        <button class="submit">Confirm</button>
      </form>
    </div>

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
