<?php
require '/u/ashorn49/openZdatabase.php';

$loggedInUserInfoQuery = $database->prepare('
	SELECT
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
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>[number]</td>
            <td>[name]</td>
            <td>[date]</td>
            <td>[price]</td>
            <td>[Winning/Place A Bid/Won/Better Luck Next Time]</td>
          </tr>
        </tbody>
      </table>    

      <div class="floatLeft marginMedium">
        <h3>Billing Info</h3>
        <dl>
          <dt>Name: </dt>
          <dd>[name]</dd>
          <dt>Type: </dt>
          <dd>[type]</dd>
          <dt>Number:</dt>
          <dd>xxxx-xxxx-xxxx-[nums]</dd>
          <dt>Exp Mo:</dt>
          <dd>[month]</dd>
          <dt>Exp Day</dt>
          <dd>[day]</dd>
        </dl>
      </div>

      <div class="floatLeft marginMedium">
        <h3>Billing Address</h3>
        <dl>
          <dt>Name: </dt>
          <dd>[name]</dd>
          <dt>Street: </dt>
          <dd>[addr]</dd>
          <dt>Ciy:</dt>
          <dd>[city]</dd>
          <dt>State:</dt>
          <dd>[state]</dd>
          <dt>Zip</dt>
          <dd>[zip]</dd>
        </dl>
      </div>

      <div class="floatLeft marginMedium">
        <h3>Shipping Address</h3>
        <dl>
          <dt>Name: </dt>
          <dd>[name]</dd>
          <dt>Street: </dt>
          <dd>[addr]</dd>
          <dt>Ciy:</dt>
          <dd>[city]</dd>
          <dt>State:</dt>
          <dd>[state]</dd>
          <dt>Zip</dt>
          <dd>[zip]</dd>
        </dl>
      </div>

      <form class="clear">
        <button class="cancel" formaction="myAccount.php" formnovalidate="formnovalidate">Cancel</button>
        <button class="cancel" formaction="pay.php" formnovalidate="formnovalidate">Incorrect Information</button>
        <button class="submit" formaction="successNotification.php">Confirm</button>
      </form>
    </div>

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
