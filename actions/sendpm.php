<?php
require('/var/www/civ/other/req.php');
$_POST['message'] = strip_tags($_POST['message']);
$query = "INSERT INTO pms (sender,receiver,message,unread, time) values(?, ?, ?, 1, NOW())";
$stmt = mysqli_stmt_init($con);
$stmt->prepare($query);
$stmt->bind_param('sss', $u, $_POST['to'], $_POST['message']);
$stmt->execute();
errorOut("Successfully sent PM", "success", "/actions/viewpm.php");
?>