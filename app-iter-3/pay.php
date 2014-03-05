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
    <script src="utils.js"></script>
    <script src="validatePayForm.js"></script>
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

      <form action="confirmPayment.php" method="post" id="main_form" onsubmit="return checkPayForm();">
<?php
$selectedItems = $_POST['items'];

if(count($_POST['items']) < 1){
?>
	<script>
		alert("You must select at least one item to pay for.\nTo proceed to this page please select at least one item.");
		window.location.assign("myAccount.php");
	</script>
<?php
}

foreach($selectedItems as $curr) {
?>
        <input type="hidden" name="items[]" value="<?=htmlspecialchars($curr)?>" /> 
<?php
}
?>
        <fieldset class="floatLeft">
          <legend>Billing Address</legend>
          <dl>
            <dt>Name</dt>
            <dd>
              <input name="bill_name" type="text" id="bill_name" onblur="checkBillName()" />
              <div class="error_msg" id="bill_name_err"></div>
            </dd>
            <dt>Street Address line 1</dt>
            <dd>
              <input name="bill_addr_1" type="text" id="bill_addr_1" onblur="checkBillAddr()" />
              <div class="error_msg" id="bill_addr_err"></div>
            </dd>
            <dt>Street Address line 2</dt>
            <dd>
              <input name="bill_addr_2" type="text" />
            </dd>
            <dt>City</dt>
            <dd>
              <input name="bill_city" type="text" id="bill_city" onblur="checkBillCity()" />
              <div class="error_msg" id="bill_city_err"></div>
            </dd>
            <dt>State</dt>
            <dd>
              <select name="bill_state" id="bill_state" onblur="checkBillState()" onclick="checkBillState()">
              	<option value="--">--Select a State--</option>
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
              <div class="error_msg" id="bill_state_err"></div>
            </dd>
            <dt>Zip Code</dt>
            <dd>
              <input name="bill_zip" type="number" id="bill_zip" onblur="checkBillZip()" maxlength="5" />
              <div class="error_msg" id="bill_zip_err"></div>
            </dd>
          </dl>
        </fieldset>

        <fieldset class="floatLeft">
          <legend>Shipping Address</legend>
          <dl>
            <dt>Name</dt>
            <dd>
              <input name="ship_name" type="text" id="ship_name" onblur="checkShipName()" />
              <div class="error_msg" id="ship_name_err"></div>
            </dd>
            <dt>Street Address line 1</dt>
            <dd>
              <input name="ship_addr_1" type="text" id="ship_addr_1" onblur="checkShipAddr()" />
              <div class="error_msg" id="ship_addr_err"></div>
            </dd>
            <dt>Street Address line 2</dt>
            <dd>
              <input name="ship_addr_2" type="text" />
            </dd>
            <dt>City</dt>
            <dd>
              <input name="ship_city" type="text" id="ship_city" onblur="checkShipCity()" />
              <div class="error_msg" id="ship_city_err"></div>
            </dd>
            <dt>State</dt>
            <dd>
              <select name="ship_state" id="ship_state" onblur="checkShipState()" onclick="checkShipState()">
              	<option value="--">--Select a State--</option>
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
              <div class="error_msg" id="ship_state_err"></div>
            </dd>
            <dt>Zip Code</dt>
            <dd>
              <input name="ship_zip" type="number" id="ship_zip" onblur="checkShipZip()" maxlength="5"/>
              <div class="error_msg" id="ship_zip_err"></div>
            </dd>
          </dl>
        </fieldset>

        <fieldset class="floatLeft">
          <legend>Billing Information</legend>
          <dl>
            <dt>First Name</dt>
            <dd>
              <input name="pay_first_name" type="text" id="pay_first_name" onblur="checkPayFirstName()" />
              <div class="error_msg" id="pay_first_name_err"></div>
            </dd>
            <dt>Last Name</dt>
            <dd>
              <input name="pay_last_name" type="text" id="pay_last_name" onblur="checkPayLastName()" />
              <div class="error_msg" id="pay_last_name_err"></div>
            </dd>
            <dt>Card Type:</dt>
            <dd>
              <select name="pay_card_type">
                <option>Visa</option>
                <option>MasterCard</option>
                <option>Discover</option>
              </select>
            </dd>
            <dt>Credit Card Number:</dt>
            <dd>
              <input name="pay_card_no" type="number" id="pay_card_no" onblur="checkPayCardNo()" maxlength="16" />
              <div class="error_msg" id="pay_card_no_err"></div>
            </dd>
            <dt>Expiration Month:</dt>
            <dd>
              <select name="pay_exp_mo">
<?php
for ($i = 1; $i <= 12; $i++) :
?>
                <option value="<?=sprintf('%02d', $i)?>"><?=date("F", mktime(0,0,0,$i+1,0,0,0))?></option>
<?php
endfor;
?>
              </select>
            </dd>
            <dt>Expiration Year:</dt>
            <dd>
              <select name="pay_exp_yr">
<?php
$year = date("Y");
for ($i = $year; $i < $year+4;  $i++) :
?>
                <option><?=$i?></option>
<?php
endfor;
?>
              </select>
            </dd>
            <dt>Security Code:</dt>
            <dd>
              <input name="pay_cvc" type="number" id="pay_cvc" onblur="checkPayCVC()" maxlength="3" />
              <div class="error_msg" id="pay_cvc_err"></div>
            </dd>
          </dl>
        </fieldset>
        <div class="clear">
          <div class="error_msg" id="submit_err"></div>
          <button class="submit" type="submit">Submit</button>
          <button class="cancel" type="button" onclick="window.location='myAccount.php'">Cancel</button>
        </div>
      </form>
    </div>

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
