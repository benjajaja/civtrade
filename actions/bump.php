<?php
require('/var/www/civ/other/req.php');
//Execute query
$query = "SELECT * FROM offers WHERE offerid=?";
$stmt = mysqli_stmt_init($con);
$stmt->prepare($query);
$stmt->bind_param('i', $_GET['pid']);
$stmt->execute();
$row=mysqli_fetch_assoc($stmt->get_result());

//Check if they're allowed to edit this post
$now = time();
$dbDate = strtotime($row['lastbumped']);
if ($row['poster'] != $u and $level != 3) { errorOut("You can only bump your own posts", "danger"); }
else if ($now < $dbDate + 86400) {  errorOut("You can only bump your posts every 24 hours", "danger"); }
//Update lastbumped
else {
	//Execute query
	$query = "UPDATE offers SET lastbumped = NOW() WHERE offerid=?";
	$stmt = mysqli_stmt_init($con);
	$stmt->prepare($query);
	$stmt->bind_param('i', $_GET['pid']);
	$stmt->execute();
	errorOut("Successfully bumped post ID ".$_GET['pid'], "success");
}
?>