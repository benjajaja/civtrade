<?php

//You are REQUIRED to change these settings to make your website work
//--------------------------------------------------------------------

//SQL Connection initlization 
//HOST is usually 'localhost' with a VPS, if cPanel you'll USUALLY get an IP
//Example: $con = mysqli_connect('localhost', 'notRoot', 'password123', 'civ');
$con = mysqli_connect('HOST', 'USER', 'PASSWORD', 'DATABASE_NAME');

//URL - Set the BASE URL here. This includes ALL SUBDOMAINS.
//Example: $url = "coolsite.freewebhosting.com";
$url = "civtrade.com";

//OPTIONAL settings. Keeping default values will make it as close to my website as possible
//-----------------------------------------------------------------------------------------

//shouldFramebreak
//Set this to false if you do NOT want to framebreak
//Default: true
$shouldFramebreak = true;

//warnOnIE
//Set this to false if you do NOT want to display a warning message when IE 8 or below connects to the website
//WARNING: IE8 and below do NOT RENDER the website correctly as-is. It is possible to modify the website yourself to add support but it will be glitchy if you don't
//Default: true
$warnOnIE = true;

//requireAPIToken
//Set this to false if you do not want to require a token on API pages
//Default: true
$requireAPIToken = true;

//logAPIRequests
//Set this to false if you do not want to log API requests
//Default: true
$logAPIRequests = true;

//news
//This will be displayed in an alert (The things that show when you see "Successfully logged in", etc) at the top of every page, provided there is no other notice
//If empty, no news will be displayed
//Default: Empty ("" or ''), NOT null
$news = "";

//newsType
//If news is set, this changes the type. Options are default bootstrap types: warning, success, danger, primary, default, info
//Throws an error if it's an invalid type and looks ugly
//Default: warning
$newsType = "warning";

//forceNews
//If set to true, news will always show, even if a note is set to show, resulting in two alerts at the top of the screen
//Default: false
$forceNews = false;

//cityCheckType
//Changes what happens if city is detected as invalid (pulling from http://txapu.com/cities.geojson)
//Options: ignore (don't even check) and force (force the user to use a valid city name). If invalid, defaults to "force"
//Default: force
$cityCheckType = "force";

//timestamps
//Enables/disabled timestamps.
//Default: False
$timestamps = false;

//loginDelay
//To prevent brute-force attempts, it's smart to have a delay on the page that processes logins/making accounts. Only affects the loginLogic.php page, not loading the login page. Measured in milliseconds.
//This affects ALL login actions (creating accounts/changing passwords/logging in/updating settings/etc) - Anything that happens on $url/actions/loginLogic.php will have this delay.
//Default: 250
$loginDelay = 250;

//directPost
//If true, users will be shown a button to post directly to /r/civcraftexchange on any posts they create. 
//Nothing will stop them from making the post themselves, this is just convenience for the user
$directPost = true;

//logSignupIP
//If true, logs the signup IP. This can never be overwritten by the user and must be manually overwritten by someone with access to the MySQL database
//Default: true
$logSignupIP = true;

//logLoginIP
//If true, logs the IP the user last logged in with. This is overwritten every time the user logs in
//Default: true
$logLoginIP = true;

//////////////////////////////////////////////////////////////////////////////////////////
//                                                                                      //
//                         This is where the magic happens.                             //
//     If you make any cool changes, please don't forget to submit a pull request!      //
//                     https://github.com/minicl55/civtrade                             //
//                                                                                      //
//////////////////////////////////////////////////////////////////////////////////////////

//CheckLogin

if (isset($_COOKIE['user']))
{
    $userid = $_COOKIE['userID'];
    $query = "SELECT passid FROM users WHERE name=?";
    $stmt = mysqli_stmt_init($con);
    $stmt->prepare($query);
    $stmt->bind_param('s', $_COOKIE['user']);
    $stmt->execute();
    $result2=$stmt->get_result();
    $storedid = mysqli_fetch_row($result2)[0];
    if (($userid != $storedid) or ($_COOKIE['user'] == 'guest'))
    {
        setcookie("user", "", time()-360000, "/", $url);
        setcookie("userID", "", time()-3600000, "/", $url);
        unset($_COOKIE['user']);
        unset($_COOKIE['userID']);
        die(errorOut("Your session has expired so you have been logged out", "danger"));
    }
}

//Level and UserInfo
//Default user:
if (isset($_COOKIE['user'])) {
    $userToPull = $_COOKIE['user']; 
}
else {
    $userToPull = 'guest';
}

$query = "SELECT * FROM users WHERE name=?";
$stmt = mysqli_stmt_init($con);
$stmt->prepare($query);
$stmt->bind_param('s', $userToPull);
$stmt->execute();
$result2=$stmt->get_result();
$userInfo = mysqli_fetch_assoc($result2);
$level = $userInfo['level'];
if ($level == 3 and isset($_GET['level1'])) {
    $level = 1;
}


//echo top
header("Access-Control-Allow-Origin: *"); //the_gipsy wanted this for his API
echo '<link rel="stylesheet" type="text/css" href="http://'.$url.'/other/stylenew.css?59">
<head><title>CivTrade!</title></head>';
if ($userInfo['staticnav'] != 1) { echo '<body class="body">'; }
else { echo '<body class="bodystatic">'; }
echo '<script src="http://code.jquery.com/jquery.js"></script>
<script src="'.$url.'/other/bootstrap.js"></script>
<nav class="navbar';
if ($userInfo['staticnav'] != 1) { echo ' navbar-fixed-top'; }
echo ' navbar-inverse" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
      </button>
      <a class="navbar-brand" href="/">CivTrade</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="/">Offers</a></li>';
      //If the user is logged in, echo PM, login and CP
        if (isset($_COOKIE['user'])) {
            //echo '<li><a href="../actions/pm.php">Private Messages</a></li>
			echo '<li><a href="/control">Control Panel</a></li>
            <li><a href="/actions/loginLogic.php?type=logout">Log out of '.$_COOKIE['user'].'</a></li>';
        }
        //If user is NOT logged in, allow user to login
        else {
            echo '<li><a href="/control/login.php">Log in or signup</a></li>';
        }
        //If the user does not want to show the "Now open source!" at the top
        if ($userInfo['closed'] != 1) {
            echo '<li><a href="https://github.com/minicl55/civtrade">Now open-source!</a></li>';
        }
    echo '</ul></div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>';

//News
if ($news != "") {
    if ($forceNews or !isset($_COOKIE['error'])) {
        alert($news, $newsType);
    }
}

//Check for error

if (isset($_COOKIE['error'])) {
//Echo error	
    alert($_COOKIE['error'], $_COOKIE['errortype']);
//Remove cookies 
	setcookie("error", '', time()-360, "/", $url);
	setcookie("errortype", '', time()-360, "/", $url);
	unset($_COOKIE['error']);
	unset($_COOKIE['errortype']);
}

//ErrorOut()

function errorOut($error, $type = "info", $rel = "/")
{
    global $url;
	setcookie("error", $error, time()+86400, "/", $url);
	setcookie("errortype", $type, time()+86400, "/", $url);
    header("Location: http://".$url.$rel);
	exit;
}

//alert()

function alert($error, $type = "danger") {
    echo '<div align="center" class="alert alert-'.$type.' alert-dismissible center-block" role="alert" style="width:97%;">'.$error.'</div>';
}

//Framebreaker

if ($shouldFramebreak) {
    echo '<script language="JavaScript" type="text/javascript">
        if (top.location != location) {
        top.location.href = document.location.href ;
        }</script>
    ';
}

//IE

if ($warnOnIE) {
    if(preg_match('/(?i)msie/',$_SERVER['HTTP_USER_AGENT'])) {
        // if IE<=8
        echo '<script>alert("Internet explorer is not supported and may cause multiple graphical glitches");</script>';
    }
}

//CheckToken
if (getcwd() == '/var/www/civ/api' or getcwd() == '/var/www/civbeta/api') {
    //Clean the echo'd stuff in the file
    ob_end_clean();
    header('Content-Type: application/json'); //Set header
    //Require any token
    if ($requireAPIToken) {
        if (!isset($_GET['token'])) { die('Missing an API token'); }

        //Require valid token and valid user
        $query = "SELECT verified FROM users WHERE confcode = ?";
        $stmt = mysqli_stmt_init($con);
        $stmt->prepare($query);
        $stmt->bind_param('s', $_GET['token']);
        $stmt->execute();
        $result2=$stmt->get_result();
        $row = mysqli_fetch_assoc($result2);
        if ($row['verified'] != 'y') { die('Invalid API token OR your account is not verified OR your API token has been suspended'); }
    }
    //Log
    if ($logAPIRequests and $requireAPIToken) {
        $query = "INSERT INTO api (token, loc, time, page) VALUES (?, ?, NOW(), ?);";
        $stmt = mysqli_stmt_init($con);
        $stmt->prepare($query);
        $ip = $_SERVER['REMOTE_ADDR'];
        $basename = basename($_SERVER['PHP_SELF']);
        $stmt->bind_param('sss', $_GET['token'], $ip, $basename);
        $stmt->execute();
        $result2=$stmt->get_result();
    }
}

//Set user
if (isset($_COOKIE['user'])) { $u = $_COOKIE['user']; }
else { $u = 'n'; }

//Check PMs

$query = "SELECT * FROM pms WHERE (sender = ? OR receiver = ?) AND unread = 1";
$stmt = mysqli_stmt_init($con);
$stmt->prepare($query);
$stmt->bind_param('ss', $u, $u);
$stmt->execute();
$result2=$stmt->get_result();
if (mysqli_num_rows($result2) != 0 and $_SERVER['PHP_SELF'] != '/actions/viewpm.php') { alert('You have a new PM! <a href="http://'.$url.'/actions/viewpm.php">Click here to view it!</a>', 'info'); }

?>