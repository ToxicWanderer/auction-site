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
      <form action="successAdd.php" method="post" enctype="multipart/form-data">
        <fieldset>
          <legend>Auction Details</legend>
          <dl>
            <dt>Item Name:</dt>
            <dd><input type="text" name="name" required="required" /></dd>
            <dt>Starting Bid:</dt>
            <dd>$<input type="number" name="starting_bid" required="required" /></dd>
            <dt>Reserve Price:</dt>
            <dd>$<input type="number" name="reserve_bid" /><br>&nbsp;&nbsp;&nbsp;* a value of $0.00 denotes that there is no reserve price</dd>
            <dt>Category:</dt>
            <dd>
              <select name="category">
                <option value="0">--Choose a category--</option>
<?php
foreach ($categories as $curr):
?>
              <option value="<?=htmlspecialchars($curr['ITEM_CATEGORY_ID'])?>"><?=htmlspecialchars($curr['NAME'])?></option>
<?php
endforeach;
?>
            </select>
            </dd>
            <dt>Item Description:</dt>
            <dd><textarea name="description" required="required"></textarea></dd>
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
              <select name="month">
<?php
for ($i = 1; $i <= 12; $i++) :
?>
                <option value="<?=sprintf('%02d', $i)?>"><?=date("F", mktime(0,0,0,$i+1,0,0,0))?></option>
<?php
endfor;
?>
              </select>
              <select name="day">
<?php
for ($i = 1; $i <= 31; $i++):
?>
                <option><?=sprintf('%02d', $i)?></option>
<?php
endfor;
?>
              </select>
            </dd>
            <dt>Upload Photo:</dt>
            <dd><input type="file" name="photo" accept="image/*" /></dd>
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
