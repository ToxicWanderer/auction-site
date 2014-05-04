<?php
require 'db.php';
require 'closeAuction.php';
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

/*if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') { 
	// we're not running on https
	header('Location: https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	exit (0);
} elseif (isset($_SESSION['user_id'])) { 
	header('Location: listings.php');
	exit (0);
} */

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
    <link rel="stylesheet" type="text/css" href="/styles.css" />
    <meta charset="utf-8" />
    <script src="utils.js"></script>
    <script src="validateRegisterForm.js"></script>
  </head>

  <body>
    <header>
<?php
if(!isset($_SESSION['user_id'])):
?>
      <div>
        <form action="successLogin.php" class="login" method="post">
          <fieldset class="login">
            <legend>Sign In</legend>
            <label>Username</label>
            <input type="text" name="username" required="required" />
            <label>Password</label>
            <input type="password" name="pwd" required="required" />
            <button class="login" type="submit">Login</button>
          </fieldset>
        </form>
      </div>
<?php
else:
?>
      <div class="loginInfo">
        You are logged in as <a href="myAccount.php"><?=$_SESSION['first_name']?></a>. (<a href="successLogout.php">Logout</a>)
      </div>
<?php
endif;
?>
	  <h1 class="title"><a href="index.php">Keith's Auction Site</a></h1>

<?=$_SERVER['HTTPS']?>

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

      <form action="successRegister.php" method="POST" onsubmit="return checkRegisterForm()">
        <fieldset class="register">
          <legend>Register as New User</legend>
          <div>
            <label>First Name</label>
            <input name="first_name" class="name" type="text" id="first_name" onblur="checkFirstName()"/>
            <label>Last Name</label>
            <input name="last_name" class="name" type="text" id="last_name" onblur="checkLastName()"/>
            <div class="error_msg center" id="first_name_err"></div>
            <div class="error_msg center" id="last_name_err"></div>
          </div>
          <div>
            <label class="floatLabel">Email</label>
            <input name="email_1" type="email" id="email_1" onblur="checkEmail1()"/>
            <div class="error_msg" id="email_1_err"></div>
          </div>
          <div>
            <label class="floatLabel">Re-enter Email</label>
            <input name="email_2" type="email" id="email_2" onblur="checkEmail2()"/>
            <div class="error_msg" id="email_2_err"></div>
          </div>
          <div>
            <label class="floatLabel">Username</label>
            <input name="username" type="text" id="username" onblur="checkUsername()"/>
            <div class="error_msg" id="username_err"></div>
          </div>
          <div>
            <label class="floatLabel">Password</label>
            <input name="pwd_1" type="password" id="pwd_1" onblur="checkPwd1()"/>
            <div class="error_msg" id="pwd_1_err"></div>
          </div>
          <div>
            <label class="floatLabel">Re-enter Password</label>
            <input name="pwd_2" type="password" id="pwd_2" onblur="checkPwd2()"/>
            <div class="error_msg" id="pwd_2_err"></div>
          </div>
          <div class="center clear">
            <input name="accept_terms" type="checkbox" required="required"/>
            I agree to the <a href="termsAndConditions.php" target="_blank">Terms and Conditions</a>
          </div>
          <div class="error_msg center" id="submit_err"></div>
          <div class="center clear">
            <button class="submit" type="submit">Register</button>
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
