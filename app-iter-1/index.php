<?php
require '/u/ashorn49/openZdatabase.php';

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
      <div>
        <form class="login">
          <fieldset class="login">
            <legend>Sign In</legend>
            <label>Username</label>
            <input type="text" required="required" />
            <label>Password</label>
            <input type="password" required="required" />
            <button class="login" formaction="listings.php">Login</button>
          </fieldset>
        </form>
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

    <div class="pageContent">
      <div class="trending">
        <h2>Recently Added</h2>
        <ul>
<?php
$auctionsQuery = $database->prepare('
    SELECT
		AUCTION_ID,
        ITEM_NAME
        FROM AUCTION
        WHERE STATUS = 1
		ORDER BY AUCTION_ID DESC
		LIMIT 4;
    ');
$auctionsQuery->execute();
$auctions = $auctionsQuery->fetchAll();
$auctionsQuery->closeCursor();

foreach ($auctions as $auction) :
?>
          <li class="trending"><a href="item.php?id=<?=htmlspecialchars($auction['AUCTION_ID'])?>"><?=htmlspecialchars($auction['ITEM_NAME'])?></a></li>
<?php
endforeach;
?>
        </ul>
        <h2>Popular Now</h2>
        <ul>
<?php
$auctionsQuery = $database->prepare('
    SELECT
		AUCTION_ID,
        ITEM_NAME
        FROM AUCTION
        WHERE STATUS = 1
		ORDER BY RAND()
		LIMIT 4;
    ');
$auctionsQuery->execute();
$auctions = $auctionsQuery->fetchAll();
$auctionsQuery->closeCursor();

foreach ($auctions as $auction) :
?>
          <li class="trending"><a href="item.php?id=<?=htmlspecialchars($auction['AUCTION_ID'])?>"><?=htmlspecialchars($auction['ITEM_NAME'])?></a></li>
<?php
endforeach;
?>
        </ul>
      </div>

      <form>
        <fieldset class="register">
          <legend>Register as New User</legend>
          <div>
            <label>First Name</label>
            <input class="name" type="text" required="required"/>
            <label>Last Name</label>
            <input class="name" type="text" required="required"/>
          </div>
          <div>
            <label class="floatLabel">Email</label>
            <input type="email" required="required"/>
          </div>
          <div>
            <label class="floatLabel">Re-enter Email</label>
            <input type="email" required="required"/>
          </div>
          <div>
            <label class="floatLabel">Username</label>
            <input type="text" required="required"/>
          </div>
          <div>
            <label class="floatLabel">Password</label>
            <input type="password" required="required"/>
          </div>
          <div>
            <label class="floatLabel">Re-enter Password</label>
            <input type="password" required="required"/>
          </div>
          <button class="submit" formaction="termsAndConditions.php">Register</button>
        </fieldset>
      </form>
    </div>

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
