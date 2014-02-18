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

$categoriesQuery = $database->prepare('
	SELECT
		ITEM_CATEGORY_ID,
		NAME
		FROM ITEM_CATEGORY;
	');
$categoriesQuery->execute();
$categories = $categoriesQuery->fetchAll();
$categoriesQuery->closeCursor();
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

      <form class="search" action="listings.php" method="get">
        <fieldset class="search">
          <legend>Search</legend>
          <select name="category">
            <option value='0'>All Categories</option>
<?php
foreach ($categories as $curr):
?>
            <option value="<?=htmlspecialchars($curr['ITEM_CATEGORY_ID'])?>"><?=htmlspecialchars($curr['NAME'])?></option>
<?php
endforeach;
?>
          </select>
          <input class="search" name="query" type="search" />
          <button class="search" type="submit" formaction="listings.php">Search</button>
        </fieldset>
      </form>
    </header>

    <img class="medium" src="notFound.jpg" alt="User Profile Image"/>

	<h2 class="pageTitle">My Account -- <?=htmlspecialchars($loggedInUserInfo['FIRST_NAME'])." ".htmlspecialchars($loggedInUserInfo['LAST_NAME'])?></h2>

    <div class="pageContent">
      <h4><a href="addItem.php">Put a New Item up for Auction</a> || <a href="listings.php">View Listings</a></h4>

      <br />

      <h3>Unpaid Auctions</h3>
      <form>
        <table>
          <thead>
            <tr>
              <th>Item ID.</th>
              <th>Item Name</th>
              <th>Date Closed</th>
              <th>Price</th>
              <th>Pay</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td><button class="search" formaction="pay.php">Pay</button></td>
            </tr>
          </tfoot>
          <tbody>
            <tr>
              <td>[<a href="item.php">number</a>]</td>
              <td>[<a href="item.php">name</a>]</td>
              <td>[date closed]</td>
              <td>[price]</td>
              <td><input type="checkbox" /></td>
            </tr>
          </tbody>
        </table>    
      </form>

      <h3>Watched Items</h3>
      <table>
        <thead>
          <tr>
            <th>Item ID.</th>
            <th>Item Name</th>
            <th>Close Date</th>
            <th>Current Price</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>[<a href="item.php">number</a>]</td>
            <td>[<a href="item.php">name</a>]</td>
            <td>[date]</td>
            <td>[price]</td>
            <td>[Winning/Place A Bid/Won/Better Luck Next Time]</td>
          </tr>
        </tbody>
      </table>    

      <h3>My Auctions</h3>
      <table>
        <thead>
          <tr>
            <th>Item ID.</th>
            <th>Item Name</th>
            <th>Close Date</th>
            <th>Current Price</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>[<a href="item.php">number</a>]</td>
            <td>[<a href="item.php">name</a>]</td>
            <td>[date]</td>
            <td>[current price]</td>
            <td>[Open/Closed]</td>
          </tr>
        </tbody>
      </table>    
    </div>

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
