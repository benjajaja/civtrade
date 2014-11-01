<?php
    if (isset($_GET['code']))
    {
        header('Content-Type: text/plain');
        //HOST is usually 'localhost' with a VPS, if cPanel you'll usually get an IP
        $con = mysqli_connect('HOST','USERNAME','PASSWORD','DATABASE_NAME');
        //If it's numeric
        if (is_numeric($_GET['code'])) {
		
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
        //Not numeric 
        else {
            echo 'Codes are 5 digit numbers';
            http_response_code(406);
        }
    }
    else {
        
    }
?>