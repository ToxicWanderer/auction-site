<?php
require 'db.php';
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
	  <h1 class="title"><a href="index.php">Keith's Auction Site</a></h1>
      <div style="clear">
        <form action="successLogin.php" method="post">
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
    </header>

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
