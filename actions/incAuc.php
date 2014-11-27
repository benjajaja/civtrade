<?php
    require('/var/www/civ/other/req.php');
	//Force auction ID
	if (!isset($_GET['id'])) {
		errorOut('Missing auction ID.');
	}
	else {
		//Get post info
		$query = "SELECT * FROM offers WHERE offerid = ?";
		$stmt = mysqli_stmt_init($con);
		mysqli_stmt_prepare($stmt,$query);
		mysqli_stmt_bind_param($stmt, "i", $_GET['id']);
		mysqli_stmt_execute($stmt);
		$row = mysqli_fetch_assoc($stmt->get_result());
		if(!empty($row['aucinc'])) { 
			//Set values
			$query = "UPDATE offers SET wantamt = ?,lastbidder = ? WHERE offerid= ?";
			$stmt = mysqli_stmt_init($con);
			mysqli_stmt_prepare($stmt,$query);
			$upTo = $row['wantamt'] + $row['aucinc'];
			mysqli_stmt_bind_param($stmt, "isi", $upTo, $_COOKIE['user'], $_GET['id']);
			mysqli_stmt_execute($stmt);
			errorOut("Successfully posted bid", "success");			
		}
		else { 
			errorOut('Post is not an auction.');
		}
	}
?>