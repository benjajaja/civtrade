<?php
	/*
		+----------+--------------+------+-----+---------+----------------+
		| Field    | Type         | Null | Key | Default | Extra          |
		+----------+--------------+------+-----+---------+----------------+
		| sender   | varchar(128) | YES  |     | NULL    |                |
		| receiver | varchar(128) | YES  |     | NULL    |                |
		| post     | int(11)      | YES  |     | NULL    |                |
		| message  | longtext     | YES  |     | NULL    |                |
		| unread   | tinyint(1)   | NO   |     | 1       |                |
		| id       | int(11)      | NO   | PRI | NULL    | auto_increment |
		| time     | datetime     | YES  |     | NULL    |                |
		+----------+--------------+------+-----+---------+----------------+
	*/
	require('/var/www/civ/other/req.php');
	//Set place
	if (isset($_POST['postID'])) { $place = $_POST['postID']; } 
	else { $place = ''; }
	//Query
	$query = "INSERT INTO pms (sender,receiver,post,message,unread,time) VALUES (?, ?, ?, ?, 0, NOW())";
	$stmt = mysqli_stmt_init($con);
	mysqli_stmt_prepare($stmt,$query);
	mysqli_stmt_bind_param($stmt, "ssss", $_COOKIE['user'], $_POST['to'], $_POST['postID'], $_POST['comment']);
	mysqli_stmt_execute($stmt);
	errorOut("Successfully sent message", "success", "/actions/pm.php");
?>