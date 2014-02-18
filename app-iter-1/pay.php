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

	<h2 class="pageTitle">Pay for an Auction</h2>

    <div class="pageContent">
      <form>
        <fieldset class="floatLeft">
          <legend>Billing Address</legend>
          <dl>
            <dt>Name</dt>
            <dd><input type="text" required="required" /></dd>
            <dt>Street Address line 1</dt>
            <dd><input type="text" required="required" /></dd>
            <dt>Street Address line 2</dt>
            <dd><input type="text" required="required" /></dd>
            <dt>City</dt>
            <dd><input type="text" required="required" /></dd>
            <dt>State</dt>
            <dd>
              <select>
                <option>Alabama</option>
                <option>Alaska</option>
                <option>Arizona</option>
                <option>Arkansas</option>
                <option>...</option>
                <option>Texas</option>
                <option>...</option>
              </select>
            </dd>
            <dt>Zip Code</dt>
            <dd><input type="number" required="required" /></dd>
          </dl>
        </fieldset>

        <fieldset class="floatLeft">
          <legend>Shipping Address</legend>
          <dl>
            <dt>Name</dt>
            <dd><input type="text" required="required" /></dd>
            <dt>Street Address line 1</dt>
            <dd><input type="text" required="required" /></dd>
            <dt>Street Address line 2</dt>
            <dd><input type="text" required="required" /></dd>
            <dt>City</dt>
            <dd><input type="text" required="required" /></dd>
            <dt>State</dt>
            <dd>
              <select>
                <option>Alabama</option>
                <option>Alaska</option>
                <option>Arizona</option>
                <option>Arkansas</option>
                <option>...</option>
                <option>Texas</option>
                <option>...</option>
              </select>
            </dd>
            <dt>Zip Code</dt>
            <dd><input type="number" required="required" /></dd>
          </dl>
        </fieldset>

        <fieldset class="floatLeft">
          <legend>Billing Information</legend>
          <dl>
            <dt>First Name</dt>
            <dd><input type="text" required="required" /></dd>
            <dt>Last Name</dt>
            <dd><input type="text" required="required" /></dd>
            <dt>Card Type:</dt>
            <dd>
              <select>
                <option>Visa</option>
                <option>MasterCard</option>
                <option>Discover</option>
              </select>
            </dd>
            <dt>Credit Card Number:</dt>
            <dd><input type="number" required="required" /></dd>
            <dt>Expiration Month:</dt>
            <dd><input type="number" required="required" /></dd>
            <dt>Expiration Day:</dt>
            <dd><input type="number" required="required" /></dd>
            <dt>Security Code:</dt>
            <dd><input type="number" required="required" /></dd>
          </dl>
        </fieldset>
        <div class="clear">
          <button class="submit" formaction="confirmPayment.php">Submit</a>
          <button class="cancel" formaction="myAccount.php" formnovalidate>Cancel</a>
        </div>
      </form>
    </div>

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
