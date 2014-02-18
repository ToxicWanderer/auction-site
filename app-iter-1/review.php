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

	<h2 class="pageTitle">Review a User</h2>

    <div class="pageContent">
      <form class="review">
        <fieldset>
          <legend><a href="user.php">abc123</a></legend>
          <br />
          <label class="clear">Reliability</label>
          <select class="floatRight">
            <option>5</option>
            <option>4</option>
            <option>3</option>
            <option>2</option>
            <option>1</option>
          </select>
          <br />
          <br />
          <label class="clear">Honesty of Product Quality Assessment</label>
          <select class="floatRight">
            <option>5</option>
            <option>4</option>
            <option>3</option>
            <option>2</option>
            <option>1</option>
          </select>
          <br />
          <br />
          <label class="clear">Timlieness of Shipping</label>
          <select class="floatRight">
            <option>5</option>
            <option>4</option>
            <option>3</option>
            <option>2</option>
            <option>1</option>
          </select>
          <br />
          <br />
          <label class="clear">Overall Rating</label>
          <select class="floatRight">
            <option>5</option>
            <option>4</option>
            <option>3</option>
            <option>2</option>
            <option>1</option>
          </select>
          <br />
          <br />
          <label>Would you buy from this user again?</label>
          <input type="radio" name="satisfaction" checked="true" /><label>Yes</label>
          <input type="radio" name="satisfaction" /><label>No</label>
          <br />
          <br />
          <div class="center">
            <button class="cancel" formaction="user.php">Cancel</button>
            <button class="submit" formaction="successNotification.php">Submit</button>
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
