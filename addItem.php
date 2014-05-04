<?php
require 'httpsRedirect.php';
require 'db.php';
session_start();

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
	<h2 class="pageTitle">Add an Item</h2>
    <div class="pageContent">
      <form action="successAdd.php" method="post" id="main_form" onsubmit="return checkAddForm()" enctype="multipart/form-data">
        <fieldset>
          <legend>Auction Details</legend>
          <dl>
            <dt>Item Name:</dt>
            <dd>
              <input type="text" name="name" id="name" onblur="checkName()"/>
              <span class="error_msg" id="name_err"></span>
            </dd>
            <dt>Starting Bid:</dt>
            <dd>
              $<input type="number" name="starting_bid" id="start_bid" onblur="checkStartBid()" />
              <span class="error_msg" id="start_bid_err"></span>
            </dd>
            <dt>Reserve Price:</dt>
            <dd>
              $<input type="number" name="reserve_bid" id="reserve" onblur="checkReserve()" />
              <span class="error_msg" id="reserve_err"></span>
            </dd>
            <dd>&nbsp;&nbsp;&nbsp;* a value of $0.00 denotes that there is no reserve price</dd>
            <dt>Category:</dt>
            <dd>
              <select name="category" id="category" onblur="checkCategory()" onclick="checkCategory()">
                <option value="0">--Choose a category--</option>
<?php
foreach ($categories as $curr):
?>
                <option value="<?=htmlspecialchars($curr['ITEM_CATEGORY_ID'])?>"><?=htmlspecialchars($curr['NAME'])?></option>
<?php
endforeach;
?>
              </select>
              <span class="error_msg" id="category_err"></span>
            </dd>
            <dt>Item Description:</dt>
            <dd>
              <textarea name="description" id="desc" onblur="checkDesc()"></textarea>
              <div class="error_msg" id="desc_err"></div>
            </dd>
            <dt>Condition:</dt>
            <dd>
              <select name="condition">
<?php
foreach ($conditions as $curr):
?>
                <option value="<?=htmlspecialchars($curr['ITEM_CONDITION_ID'])?>"><?=htmlspecialchars($curr['NAME'])?></option>
<?php
endforeach;
?>
              </select>
            </dd>
            <dt>Close Auction At:</dt>
            <dd>
              <select name="hour">
                <option>12</option>
<?php
for ($i = 1; $i <= 11; $i++):
?>
                <option><?=sprintf('%02d', $i)?></option>
<?php
endfor;
?>
              </select>:
              <select name="minute">
<?php
for ($i = 0; $i < 60; $i+=5):
?>
                <option><?=sprintf('%02d', $i)?></option>
<?php
endfor;
?>
              </select>
              <select name="am_pm">
                <option>am</option>
                <option>pm</option>
              </select>
              <label>on</label>
              <script>
                $(function() { $( "#datepicker" ).datepicker({ 
		      		dateFormat: "yy-mm-dd",
					minDate: 0,
					maxDate: "+2M",
		      		showButtonPanel:true }); });
              </script>
              <input type="text" id="datepicker" name="date" value="<?=date("Y-m-d")?>" readonly="readonly"/>
            </dd>
            <dt>Upload Photo:</dt>
            <dd><input type="file" name="photo" accept="image/*" /></dd>
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
