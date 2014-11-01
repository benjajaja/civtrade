<?php
require('/var/www/civ/other/req.php');
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
            $passID = rand(10000,99999);
            $confCode = rand(10000,99999);
			/*$query = "INSERT INTO users (name,level,passhash,passid,verified,rep,confcode) VALUES 
            ('".$_POST['user']."',
            1,'".
            password_hash($_POST['pass'], PASSWORD_DEFAULT)."',".
            $passID.", 
            'n',
             0,
             ".$confCode.");";*/
            $query = "INSERT INTO users (name,level,passhash,passid,verified,rep,confcode) VALUES (?, 1, ?, ".$passID.", 'n', 0, ".$confCode.");";
			$stmt = mysqli_stmt_init($con);
			$stmt->prepare($query);
			$newPass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
			$stmt->bind_param('ss', $_POST['user'], $newPass);
			$stmt->execute();
            setcookie("user", $_POST['user'], time()+86400, "/", $url);
            setcookie("userID", $passID, time()+86400, "/", $url);
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
            $passID = rand(10000,99999);
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
			$stmt->bind_param('ss', $passID, $_COOKIE['user']);
			$stmt->execute();
            setcookie("userID", $passID, time()+86400, "/", $url);
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
	$rel = 'false';
	$white = 'false';
	$def = 'false';
	if (isset($_POST['relativetime'])) { $rel = 'true'; }
	if (isset($_POST['whtienav'])) { $white = 'true'; }
	if (isset($_POST['defaultsearch'])) { $def = 'true'; }
	$query = "UPDATE users SET relativetime = ?, whitenav = ?, defaultsearch = ? WHERE name=?";
	$stmt = mysqli_stmt_init($con);
	$stmt->prepare($query);
	$stmt->bind_param('ssss', $rel, $white, $def, $_COOKIE['user']);
	$stmt->execute();
	errorOut("Successfully updated your settings", "success", "/control");
}

//Otherwise
else { 
    errorOut("Oops! Something went wrong, please try again", "danger");
}
?>