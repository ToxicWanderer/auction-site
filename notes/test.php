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
        You are logged in as <a href="myAccount.html">Bill</a>. (<a href="index.html">Logout</a>)
      </div>

	  <h1 class="title"><a href="index.html">Keith's Auction Site</a></h1>

      <form class="search">
        <fieldset class="search">
          <legend>Search</legend>
          <select>
            <option>All Categories</option>
            <option>Clothing</option>
            <option>Electronics</option>
            <option>Home</option>
            <option>...</option>
          </select>
          <input class="search" type="search" />
          <select>
            <option>Item Name</option>
            <option>Seller Name</option>
          </select>
          <a href="listings.html">Submit</a>
        </fieldset>
      </form>
    </header>

	<h2 class="pageTitle">Title</h2>
    <div class="pageContent">
      <p>Good morning, it's <?= strftime("%X") ?>.</p>
      <p>Good morning, it's <?php echo strftime("%X"); ?>.</p>

        <?php
        $currTime = localtime(time(), true);
        if ($currTime['tm_hour'] < 11) {
        	echo "<strong>Go back to bed.</strong><br>\n";
        }
        ?>
        <?php
        $currTime = localtime(time(), true);
        if ($currTime['tm_hour'] < 11) :
        ?>
        	<strong>Go back to bed.</strong><br>
        <?php
        endif;
        ?>
    </div>

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
