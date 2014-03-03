<?php
require '/u/ashorn49/openZdatabase.php';
session_start();

if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') { 
	// we're not running on https
	header('Location: https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	exit (0);
} 

$expiredAuctionsQuery = $database->prepare('
	SELECT
		AUCTION_ID,
		CURRENT_BID_ID
		FROM AUCTION
		WHERE CLOSE_TIME <= :now;
	');
$expiredAuctionsQuery->bindValue(':now', date('Y-m-d H:i:s'), PDO::PARAM_STR);
$expiredAuctionsQuery->execute();
$expiredAuctions = $expiredAuctionsQuery->fetchAll();
$expiredAuctionsQuery->closeCursor();

foreach ($expiredAuctions as $curr){
	$updateStatusQuery = $database->prepare('
	    UPDATE AUCTION
			SET STATUS = :status
			WHERE AUCTION_ID = :auctionId;
		');
	$updateStatusQuery->bindValue(':status', isset($curr['CURRENT_BID_ID']) ? 3 : 4, PDO::PARAM_STR);
	$updateStatusQuery->bindValue(':auctionId', $curr['AUCTION_ID'], PDO::PARAM_STR);
	$updateStatusQuery->execute();
	$updateStatusQuery->closeCursor();
}
?>
