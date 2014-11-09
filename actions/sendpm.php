<?php
	require('/var/www/civ/other/req.php');
	//Set place
	if (isset($_POST['postID'])) { $place = $_POST['postID']; } 
	else { $place = ''; }
	//Query
	$query = "INSERT INTO pms (sender,receiver,post,message,unread,time) VALUES (?, ?, ?, ?, 1, NOW());";
	$stmt = mysqli_stmt_init($con);
	mysqli_stmt_prepare($stmt,$query);
	mysqli_stmt_bind_param($stmt, "ssss", $_COOKIE['user'], $_POST['to'], $place, $_POST['comment']);
	mysqli_stmt_execute($stmt);
	errorOut("Successfully sent message", "success", "/actions/pm.php");
?>