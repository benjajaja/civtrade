<?php
require('/var/www/civ/other/req.php');
$_POST['message'] = strip_tags($_POST['message']);
$_POST['to'] = strip_tags($_POST['to']);
$u = strip_tags($u);
//Check that input is valid 
$query = "SELECT name FROM users WHERE name LIKE ?";
$stmt = mysqli_stmt_init($con);
$stmt->prepare($query);
$stmt->bind_param('s', $_POST['to']);
$stmt->execute();
$row = $stmt->get_result();
if (mysqli_num_rows($row) == 0 or strlen($_POST['message']) == 0) {
	errorOut('Nonexistant user or empty message', "danger", "/actions/viewpm.php?to=".$_POST['to'].'&msg='.$_POST['message']);
}
else {
	$query = "INSERT INTO pms (sender,receiver,message,unread, time) values(?, ?, ?, 1, NOW())";
	$stmt = mysqli_stmt_init($con);
	$stmt->prepare($query);
	$stmt->bind_param('sss', $u, $_POST['to'], $_POST['message']);
	$stmt->execute();
	errorOut("Successfully sent PM", "success", "/actions/viewpm.php");
}
?>