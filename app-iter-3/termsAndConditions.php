<?php
require '/u/ashorn49/openZdatabase.php';
session_start();

if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') { 
	// we're not running on https
	header('Location: https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
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
	  <h1 class="title"><a href="index.php">Keith's Auction Site</a></h1>
    </header>

	<h2 class="pageTitle">Terms and Conditions</h2>

    <div class="pageContent">
      <p>If this were a real ecommerce site, terms and conditions would go here.</p>
      <p>Note: This is not a real ecommerce site, please do not use for actual business.</p>

      <form>
        <button class="submit" type="button" onclick="window.close()">Close</a>
      </form>
    </div>

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
