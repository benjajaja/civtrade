<?php
	require('/var/www/civbeta/other/req.php');
    ob_end_clean();    
    //If there is no token GET value, fake a 404
    if (!isset($_GET['token'])) { fake404(); }
    
    //Confirm token is correct
	$query = "SELECT confcode FROM users WHERE name = ?";
	$user = "GipsyKing";
	$stmt = mysqli_stmt_init($con);
	$stmt->prepare($query);
	$stmt->bind_param('s', $user);
	$stmt->execute();
	$result2=$stmt->get_result();
	$row = mysqli_fetch_assoc($result2);
    if ($_GET['token'] == $row['confcode']) {
        if (isset($_GET['code']))
        {
            header('Content-Type: text/plain');
            //Unconfirm
            if ($_GET['user'] == 'minicl55' and $_GET['code'] == 'unconf') {
                $query = "UPDATE users SET verified='n' WHERE name='minicl55'";
                $stmt = mysqli_stmt_init($con);
                $stmt->prepare($query);
                $stmt->execute();
                die ("Your account has been unverified.");
            }
            //Reset password
            if ($_GET['code'] == 'resetpw') {
                $rnd = '';
                $characters = "abcdefghijklmnopqrstuvwxyz0123456789";
                for ($i = 0; $i < 12; $i++) {
                  $rnd .= $characters[rand(0, strlen($characters) - 1)];
                }
                //Update password
                $query = "UPDATE users SET passhash = ? WHERE name = ?";
                $passhash = password_hash($rnd, PASSWORD_DEFAULT);
                $stmt = mysqli_stmt_init($con);
                $stmt->prepare($query);
                $stmt->bind_param('ss', $passhash, $_GET['user']);
                $stmt->execute();
                
                //Update passid
                
                $query = "UPDATE users SET passid = ? WHERE name = ?";
                $passhash = password_hash($rnd, PASSWORD_DEFAULT);
                $stmt = mysqli_stmt_init($con);
                $stmt->prepare($query);
                $stmt->bind_param('ss', $rnd, $_GET['user']);
                $stmt->execute();
                http_response_code(200);
                die('Your password has been reset to '.$rnd);
            }
			//Get their confcode
			$query = "SELECT confcode FROM users WHERE name= ?";
			$stmt = mysqli_stmt_init($con);
			$stmt->prepare($query);
			$stmt->bind_param('s', $_GET['user']);
			$stmt->execute();
			$result2=$stmt->get_result();
			$codeSql = (mysqli_fetch_row($result2)[0]);

			//Get if they're verified already
			$query = "SELECT verified FROM users WHERE name= ?";
			$stmt = mysqli_stmt_init($con);
			$stmt->prepare($query);
			$stmt->bind_param('s', $_GET['user']);
			$stmt->execute();
			$result2=$stmt->get_result();
			$checkVer = (mysqli_fetch_row($result2)[0]);
			//If code is valid
			if ($codeSql == $_GET['code']) {
				//If they're already verified
				if ($checkVer == 'y') {
					echo 'You have already verified your account';
					http_response_code(406);
				}
				else {
					$query = "UPDATE users SET verified='y' WHERE name= ?";
					$stmt = mysqli_stmt_init($con);
					$stmt->prepare($query);
					$stmt->bind_param('s', $_GET['user']);
					$stmt->execute();
					echo 'Success, your account has been verified!';
					http_response_code(200);
				}
			}
			else {
				echo 'Invalid conformation code';
				http_response_code(406);
			}
		}
	}
    else {
        fake404();
    }
?>