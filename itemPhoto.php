<?php
require '/u/ashorn49/openZdatabase.php';

$photoQuery = $database->prepare('
	SELECT
		ITEM_PHOTO
		FROM AUCTION
		WHERE AUCTION_ID = :id;
	');
$id = htmlspecialchars($_REQUEST['id']);
$photoQuery->bindValue(':id', $id, PDO::PARAM_INT);
$photoQuery->execute();
$photo = $photoQuery->fetch();
$photoContents = $photo['ITEM_PHOTO'];
$photoQuery->closeCursor();

if(strlen($photoContents)){
	header('Content-Type: image/jpeg');
	header('Content-Length: '.strlen($photoContents));
	echo $photoContents;
} else {
	$placeholder = 'notFound.jpg';
	header('Content-Type: image/jpeg');
	header('Content-Length: '.filesize($placeholder));
	readfile($placeholder);
}
