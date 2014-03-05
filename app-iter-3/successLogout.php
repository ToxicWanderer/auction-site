<?php
require '/u/ashorn49/openZdatabase.php';
require 'password.php';
session_start();

if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== "on") {
    header('HTTP/1.1 403 Forbidden: TLS Required');
	header('Location: index.php');
    exit(1);
}
session_destroy();
session_regenerate_id(true);
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

	<h2 class="pageTitle">Success!</h2>
    <div class="pageContent">
      <p>You've been logged out</p>

      <form action="index.php">
        <button class="submit" type="submit">Continue</button>
      </form>
    </div>

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
