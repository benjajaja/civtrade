<?php
    require('/var/www/civ/other/req.php');
    //Check if valid 
    if (strtolower($cityCheckType) != "ignore") {
        if (strpos(file_get_contents('/var/cities.geojson'), $_POST['loc']) === false) {
			errorOut('Invalid city location. If you think this is wrong, please submit a new city to <a href="http://txapu.com/">Txapu</a>', 'danger', '/control');
        }
		else {
			//Make post
			$_POST['want'] = rtrim($_POST['want'], 's');
			$_POST['have'] = rtrim($_POST['have'], 's');
			$_POST['have'] = strip_tags($_POST['have']);
			$_POST['loc'] = strip_tags($_POST['loc']);
            $_POST['notes'] = strip_tags($_POST['notes']);
			if ($_POST['have'] != '' and $_POST['want'] != '' and $_POST['loc'] != '' and is_numeric($_POST['amountHave']) and is_numeric($_POST['amountWant']) and $_POST['amountWant'] >= 0 and $_POST['amountHave'] >= 0) {
				if ($_POST['mininc'] != null) {
					$query = "INSERT INTO offers (poster,have,haveamt,want,wantamt,notes,active,location,creation, aucinc, lastbidder) VALUES(?, ?, ?, ?, ?, ?, 'y', ?, NOW(), ?, ?)";
					$stmt = mysqli_stmt_init($con);
					mysqli_stmt_prepare($stmt,$query);
					mysqli_stmt_bind_param($stmt, "sssssssis", $_COOKIE['user'], $_POST['have'], $_POST['amountHave'], $_POST['want'], $_POST['amountWant'], $_POST['notes'], $_POST['loc'], $_POST['mininc'], $_COOKIE['user']);
					mysqli_stmt_execute($stmt);
					errorOut("Successfully posted your auction", "success");
				}
				else {
					$query = "INSERT INTO offers (poster,have,haveamt,want,wantamt,notes,active,location,creation) VALUES(?, ?, ?, ?, ?, ?, 'y', ?, NOW())";
					$stmt = mysqli_stmt_init($con);
					mysqli_stmt_prepare($stmt,$query);
					mysqli_stmt_bind_param($stmt, "sssssss", $_COOKIE['user'], $_POST['have'], $_POST['amountHave'], $_POST['want'], $_POST['amountWant'], $_POST['notes'], $_POST['loc']);
					mysqli_stmt_execute($stmt);
					errorOut("Successfully posted your listing", "success");
				}
			}
			else {
				errorOut("Something went wrong, please try again", "danger", "/control");
			}
		}
	}
?>