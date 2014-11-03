<?php
    require('/var/www/civ/other/req.php');
    
    //CREATE TABLE offers(poster TEXT, have TEXT, haveamt INT, want TEXT, wantamt INT, offerid INT AUTO_INCREMENT PRIMARY KEY), active CHAR,notes TEXT;
	/*
	poster   
	have     
	haveamt  
	want     
	wantamt  
	notes    
	offerid  
	active   
	location 
	creation 
	*/
    $_POST['want'] = rtrim($_POST['want'], 's');
    $_POST['have'] = rtrim($_POST['have'], 's');
    $_POST['have'] = strip_tags($_POST['have']);
    $_POST['want'] = strip_tags($_POST['want']);
	if ($_POST['have'] != '' and $_POST['want'] != '' and $_POST['loc'] != '' and is_numeric($_POST['amountHave']) and is_numeric($_POST['amountWant']) and $_POST['amountWant'] >= 0 and $_POST['amountHave'] >= 0) {
			$query = "INSERT INTO offers (poster,have,haveamt,want,wantamt,notes,active,location,creation) 
			VALUES(?,
			?,
			?,
			?,
			?,
			?,
			'y',
			?,
			NOW())";
			$stmt = mysqli_stmt_init($con);
			mysqli_stmt_prepare($stmt,$query);
			mysqli_stmt_bind_param($stmt, "sssssss", $_COOKIE['user'], $_POST['have'], $_POST['amountWant'], $_POST['amountHave'], $_POST['want'], $_POST['notes'], $_POST['loc']);
			mysqli_stmt_execute($stmt);
			errorOut("Successfully posted your listing", "success");
		}
		else {
			errorOut("Something went wrong, please try again", "danger", "/control");
		}
?>