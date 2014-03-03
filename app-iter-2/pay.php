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

	<h2 class="pageTitle">Pay for an Auction</h2>

    <div class="pageContent">

      <form action="confirmPayment.php" method="post">
<?php
$selectedItems = $_POST['items'];
foreach($selectedItems as $curr) {
?>
        <input type="hidden" name="items[]" value="<?=$curr?>" /> 
<?php
}
?>
        <fieldset class="floatLeft">
          <legend>Billing Address</legend>
          <dl>
            <dt>Name</dt>
            <dd><input name="bill_name" type="text" required="required" /></dd>
            <dt>Street Address line 1</dt>
            <dd><input name="bill_addr_1" type="text" required="required" /></dd>
            <dt>Street Address line 2</dt>
            <dd><input name="bill_addr_2" type="text" /></dd>
            <dt>City</dt>
            <dd><input name="bill_city" type="text" required="required" /></dd>
            <dt>State</dt>
            <dd>
              <select name="bill_state">
              	<option value="AL">Alabama</option>
              	<option value="AK">Alaska</option>
              	<option value="AZ">Arizona</option>
              	<option value="AR">Arkansas</option>
              	<option value="CA">California</option>
              	<option value="CO">Colorado</option>
              	<option value="CT">Connecticut</option>
              	<option value="DE">Delaware</option>
              	<option value="DC">District Of Columbia</option>
              	<option value="FL">Florida</option>
              	<option value="GA">Georgia</option>
              	<option value="HI">Hawaii</option>
              	<option value="ID">Idaho</option>
              	<option value="IL">Illinois</option>
              	<option value="IN">Indiana</option>
              	<option value="IA">Iowa</option>
              	<option value="KS">Kansas</option>
              	<option value="KY">Kentucky</option>
              	<option value="LA">Louisiana</option>
              	<option value="ME">Maine</option>
              	<option value="MD">Maryland</option>
              	<option value="MA">Massachusetts</option>
              	<option value="MI">Michigan</option>
              	<option value="MN">Minnesota</option>
              	<option value="MS">Mississippi</option>
              	<option value="MO">Missouri</option>
              	<option value="MT">Montana</option>
              	<option value="NE">Nebraska</option>
              	<option value="NV">Nevada</option>
              	<option value="NH">New Hampshire</option>
              	<option value="NJ">New Jersey</option>
              	<option value="NM">New Mexico</option>
              	<option value="NY">New York</option>
              	<option value="NC">North Carolina</option>
              	<option value="ND">North Dakota</option>
              	<option value="OH">Ohio</option>
              	<option value="OK">Oklahoma</option>
              	<option value="OR">Oregon</option>
              	<option value="PA">Pennsylvania</option>
              	<option value="RI">Rhode Island</option>
              	<option value="SC">South Carolina</option>
              	<option value="SD">South Dakota</option>
              	<option value="TN">Tennessee</option>
              	<option value="TX">Texas</option>
              	<option value="UT">Utah</option>
              	<option value="VT">Vermont</option>
              	<option value="VA">Virginia</option>
              	<option value="WA">Washington</option>
              	<option value="WV">West Virginia</option>
              	<option value="WI">Wisconsin</option>
              	<option value="WY">Wyoming</option>
              </select>
            </dd>
            <dt>Zip Code</dt>
            <dd><input name="bill_zip" type="number" required="required" /></dd>
          </dl>
        </fieldset>

        <fieldset class="floatLeft">
          <legend>Shipping Address</legend>
          <dl>
            <dt>Name</dt>
            <dd><input name="ship_name" type="text" required="required" /></dd>
            <dt>Street Address line 1</dt>
            <dd><input name="ship_addr_1" type="text" required="required" /></dd>
            <dt>Street Address line 2</dt>
            <dd><input name="ship_addr_2" type="text" /></dd>
            <dt>City</dt>
            <dd><input name="ship_city" type="text" required="required" /></dd>
            <dt>State</dt>
            <dd>
              <select name="ship_state">
              	<option value="AL">Alabama</option>
              	<option value="AK">Alaska</option>
              	<option value="AZ">Arizona</option>
              	<option value="AR">Arkansas</option>
              	<option value="CA">California</option>
              	<option value="CO">Colorado</option>
              	<option value="CT">Connecticut</option>
              	<option value="DE">Delaware</option>
              	<option value="DC">District Of Columbia</option>
              	<option value="FL">Florida</option>
              	<option value="GA">Georgia</option>
              	<option value="HI">Hawaii</option>
              	<option value="ID">Idaho</option>
              	<option value="IL">Illinois</option>
              	<option value="IN">Indiana</option>
              	<option value="IA">Iowa</option>
              	<option value="KS">Kansas</option>
              	<option value="KY">Kentucky</option>
              	<option value="LA">Louisiana</option>
              	<option value="ME">Maine</option>
              	<option value="MD">Maryland</option>
              	<option value="MA">Massachusetts</option>
              	<option value="MI">Michigan</option>
              	<option value="MN">Minnesota</option>
              	<option value="MS">Mississippi</option>
              	<option value="MO">Missouri</option>
              	<option value="MT">Montana</option>
              	<option value="NE">Nebraska</option>
              	<option value="NV">Nevada</option>
              	<option value="NH">New Hampshire</option>
              	<option value="NJ">New Jersey</option>
              	<option value="NM">New Mexico</option>
              	<option value="NY">New York</option>
              	<option value="NC">North Carolina</option>
              	<option value="ND">North Dakota</option>
              	<option value="OH">Ohio</option>
              	<option value="OK">Oklahoma</option>
              	<option value="OR">Oregon</option>
              	<option value="PA">Pennsylvania</option>
              	<option value="RI">Rhode Island</option>
              	<option value="SC">South Carolina</option>
              	<option value="SD">South Dakota</option>
              	<option value="TN">Tennessee</option>
              	<option value="TX">Texas</option>
              	<option value="UT">Utah</option>
              	<option value="VT">Vermont</option>
              	<option value="VA">Virginia</option>
              	<option value="WA">Washington</option>
              	<option value="WV">West Virginia</option>
              	<option value="WI">Wisconsin</option>
              	<option value="WY">Wyoming</option>
              </select>
            </dd>
            <dt>Zip Code</dt>
            <dd><input name="ship_zip" type="number" required="required" /></dd>
          </dl>
        </fieldset>

        <fieldset class="floatLeft">
          <legend>Billing Information</legend>
          <dl>
            <dt>First Name</dt>
            <dd><input name="pay_first_name" type="text" required="required" /></dd>
            <dt>Last Name</dt>
            <dd><input name="pay_last_name" type="text" required="required" /></dd>
            <dt>Card Type:</dt>
            <dd>
              <select name="pay_card_type">
                <option>Visa</option>
                <option>MasterCard</option>
                <option>Discover</option>
              </select>
            </dd>
            <dt>Credit Card Number:</dt>
            <dd><input name="pay_card_no" type="number" required="required" /></dd>
            <dt>Expiration Month:</dt>
            <dd><input name="pay_exp_mo" type="number" required="required" /></dd>
            <dt>Expiration Day:</dt>
            <dd><input name="pay_exp_day" type="number" required="required" /></dd>
            <dt>Security Code:</dt>
            <dd><input name="pay_cvc" type="number" required="required" /></dd>
          </dl>
        </fieldset>
        <div class="clear">
          <button class="submit">Submit</button>
          <button class="cancel" formaction="myAccount.php" formnovalidate>Cancel</button>
        </div>
      </form>
    </div>

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
