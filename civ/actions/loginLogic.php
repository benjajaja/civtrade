<?php
require('/var/www/civ/other/req.php');
//Random alphanumb generator

//TODO: Make function in req to do this
$rnd = '';
$characters = "abcdefghijklmnopqrstuvwxyz0123456789";
for ($i = 0; $i < 12; $i++) {
  $rnd .= $characters[rand(0, strlen($characters) - 1)];
}

$rndAPI = '';
for ($i = 0; $i < 12; $i++) {
  $rndAPI .= $characters[rand(0, strlen($characters) - 1)];
}

//Sleep for security purposes
sleep($loginDelay / 1000);

//Login part
if ($_GET['type'] == 'login')
{
    //Check if pass verifies
    
    //Get passhash
    $query = "SELECT passhash FROM users WHERE name= ?";
    $stmt = mysqli_stmt_init($con);
    $stmt->prepare($query);
    $stmt->bind_param('s', $_POST['user']);
    $stmt->execute();
    $result = mysqli_fetch_row($stmt->get_result());
    
    //Get passid
    
    $query = "SELECT passid FROM users WHERE name= ?";
    $stmt = mysqli_stmt_init($con);
    $stmt->prepare($query);
    $stmt->bind_param('s', $_POST['user']);
    $stmt->execute();
    $resultID = mysqli_fetch_row($stmt->get_result());
    if (password_verify($_POST['pass'], $result[0]))
    {
        setcookie("user", $_POST['user'], time()+86400, "/", $url);
        setcookie("userID", $resultID[0], time()+86400, "/", $url);
        if ($logSignupIP) {
            $query = "UPDATE users SET lastip = ?, lastlogin = NOW() WHERE name = ?";
            $stmt = mysqli_stmt_init($con);
            $stmt->prepare($query);
            $stmt->bind_param('ss', $_SERVER['REMOTE_ADDR'], $_POST['user']);
            $stmt->execute();
        }
        errorOut("Successfully logged in", "success", "/control");
    }
    else {
        errorOut("Invalid password", "danger", "/control/login.php");
    }
}

//Signup part

else if ($_GET['type'] == 'signup') {
	if (strlen($_POST['pass']) >= 8 and ($_POST['pass'] == $_POST['passConfirm'])) {
        
        //Check if exists
        $exists = false;
        $query = "SELECT name FROM users WHERE name LIKE ?";
		$stmt = mysqli_stmt_init($con);
		$stmt->prepare($query);
		$stmt->bind_param('s', $_POST['user']);
		$stmt->execute();
		$row = $stmt->get_result();
        while (mysqli_fetch_assoc($row)) {
            $exists = true;
            die(errorOut("Username is already taken.", "danger", "/control/login.php"));
        }
        if ($exists == false) {        
        //Create account
            $confCode = rand(10000,99999);
            $query = "INSERT INTO users (name,level,passhash,passid,verified,confcode,lastip,signupip,lastlogin) VALUES (?, 1, ?, '".$rnd."', 'n', '".$rndAPI."', ?, ?, NOW())";
            $stmt = mysqli_stmt_init($con);
            $stmt->prepare($query);
			$newPass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            $stmt->bind_param('ssss', $_POST['user'], $newPass, $_SERVER['REMOTE_ADDR'], $_SERVER['REMOTE_ADDR']);
            $stmt->execute();
            setcookie("user", $_POST['user'], time()+86400, "/", $url);
            setcookie("userID", $rnd, time()+86400, "/", $url);
            errorOut("Successfully logged in", "success", "/control");
        }
	}
	else {
		errorOut("Your password must be at least 8 characters long", "danger", "/control/login.php");
	}
}


//Change password
else if ($_GET['type'] == 'changepw') {
    if (strlen($_POST['newPass']) >= 8)
    {
        if ($_POST['newPass'] == $_POST['newPassConf']) {
            $newPass = password_hash($_POST['newPass'], PASSWORD_DEFAULT);
			//Update pass
            $query = "UPDATE users SET passhash= ? WHERE name= ?";
			$stmt = mysqli_stmt_init($con);
			$stmt->prepare($query);
			$stmt->bind_param('ss', $newPass, $_COOKIE['user']);
			$stmt->execute();
			
			//Update passID
            $query = "UPDATE users SET passid= ? WHERE name= ?";
			$stmt = mysqli_stmt_init($con);
			$stmt->prepare($query);
			$stmt->bind_param('ss', $rnd, $_COOKIE['user']);
			$stmt->execute();
            setcookie("userID", $rnd, time()+86400, "/", $url);
            errorOut("Successfully updated your password!", "success", "/control");
        }
        else {
            errorOut("Your passwords do not match", "danger", "/control");
        }
    }
    else {
        errorOut("Your password must be at least 8 characters long", "danger", "/control");
    }
}

//Logout part

else if ($_GET['type'] == 'logout') {
    setcookie("user", "", time()-360000, "/", $url);
    setcookie("userID", "", time()-3600000, "/", $url);
    unset($_COOKIE['user']);
    unset($_COOKIE['userID']);
    errorOut("Succesfully logged out", "success");
}

//Update settings

else if ($_GET['type'] == 'updateSettings') {
    if (isset($_POST['staticnav'])) { $static = 1; } else { $static = 0; }
    if (isset($_POST['closed'])) { $closed = 1; } else { $closed = 0; }
	$query = "UPDATE users SET closed = ?, staticnav = ? WHERE name=?";
	$stmt = mysqli_stmt_init($con);
	$stmt->prepare($query);
	$stmt->bind_param('sss', $closed, $static, $_COOKIE['user']);
	$stmt->execute();
	errorOut("Successfully updated your settings", "success", "/control");
}

//Otherwise
else { 
    errorOut("Oops! Something went wrong, please try again", "danger");
}
?>