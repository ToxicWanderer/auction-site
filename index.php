<?php
require '/u/ashorn49/openZdatabase.php';

if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') { 
	// we're not running on https
	header('Location: https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	exit (0);
} elseif (isset($_SESSION['user_id'])) {
	header('Location: listings.php');
	exit (0);
}

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

      <form action="successRegister.php" method="POST">
        <fieldset class="register">
          <legend>Register as New User</legend>
          <div>
            <label>First Name</label>
            <input name="first_name" class="name" type="text" required="required"/>
            <label>Last Name</label>
            <input name="last_name" class="name" type="text" required="required"/>
          </div>
          <div>
            <label class="floatLabel">Email</label>
            <input name="email_1" type="email" required="required"/>
          </div>
          <div>
            <label class="floatLabel">Re-enter Email</label>
            <input name="email_2" type="email" required="required"/>
          </div>
          <div>
            <label class="floatLabel">Username</label>
            <input name="username" type="text" required="required"/>
          </div>
          <div>
            <label class="floatLabel">Password</label>
            <input name="pwd_1" type="password" required="required"/>
          </div>
          <div>
            <label class="floatLabel">Re-enter Password</label>
            <input name="pwd_2" type="password" required="required"/>
          </div>
          <div class="center clear">
            <input name="accept_terms" type="checkbox" required="required"/>
            I agree to the <a href="termsAndConditions.php" target="_blank">Terms and Conditions</a>
          </div>
          <div class="center clear">
            <button class="submit">Register</button>
          </div>
        </fieldset>
      </form>

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
    </div>

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
