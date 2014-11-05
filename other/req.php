<?php

//You are REQUIRED to change these settings to make your website work
//--------------------------------------------------------------------

//SQL Connection initlization 
//HOST is usually 'localhost' with a VPS, if cPanel you'll USUALLY get an IP
//Example: $con = mysqli_connect('localhost', 'notRoot', 'password123', 'civ');
$con = mysqli_connect('HOST', 'USERNAME', 'PASSWORD', 'DATABASE_NAME');

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


//////////////////////////////////////////////////////////////////////////////////////////
//                                                                                      //
//                               === IMPORTANT ===                                      //
//              Do not edit past this point unless you know what you're doing!          //
//    If you do know what you're doing, feel free to edit. Don't foregt to submit a     //
//                   pull request if you make an improvement!                           //
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
    if ($userid != $storedid)
    {
        setcookie("user", "", time()-360000, "/", $url);
        setcookie("userID", "", time()-3600000, "/", $url);
        unset($_COOKIE['user']);
        unset($_COOKIE['userID']);
        die(errorOut("Your session has expired so you have been logged out", "danger"));
    }
}

//Fake a 404 page
function fake404() {
    echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
    <html><head>
    <title>404 Not Found</title>
    </head><body>
    <h1>Not Found</h1>
    <p>The requested URL /'.basename($_SERVER['PHP_SELF']).' was not found on this server.</p>
    <hr>
    <address>Apache/2.2.22 (Debian) Server at civtrade.com Port 80</address>
    </body></html>
    ';
    die (http_response_code(404));
}


//Level
if (isset($_COOKIE['user'])) {
    $query = "SELECT * FROM users WHERE name=?";
    $stmt = mysqli_stmt_init($con);
    $stmt->prepare($query);
    $stmt->bind_param('s', $_COOKIE['user']);
    $stmt->execute();
    $result2=$stmt->get_result();
    $userInfo = mysqli_fetch_assoc($result2);
	$level = $userInfo['level'];
    $whiteNav = 'navbar-inverse';
}
else {
	$whiteNav = 'navbar-inverse';
    $level = 0;
}

//echo top

header("Access-Control-Allow-Origin: *");
echo '<link rel="stylesheet" type="text/css" href="http://'.$url.'/other/stylenew.css?59">
<head><title>CivTrade!</title></head><body class="body">
<script src="http://code.jquery.com/jquery.js"></script>
<script src="'.$url.'/other/bootstrap.js"></script>
<nav class="navbar navbar-fixed-top '.$whiteNav.'" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">CivTrade</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="/">Offers</a></li>
        <li><a href="/control">UserCP</a></li>
      </ul>';
        if (isset($_COOKIE['user'])) {
            echo '<ul class="nav navbar-nav">
			<li><a href="../?showOwnDisabled">Show your disabled posts</a></li>';
			if ($level == 3) { 
				echo '<li><a href="../?showAllDisabled">Show all disabled posts</a></li>';
			}
            echo '<li><a href="/actions/loginLogic.php?type=logout">Log out of '.$_COOKIE['user'].'</a></li></ul>';
        }
        else {
            echo '<ul class="nav navbar-nav">
            <li><a href="/control/login.php">Log in</a></li>
            </ul>';
        }
        echo '<ul class="nav navbar-nav"><li><a href="https://github.com/minicl55/civtrade">Now open-source!</a></li></ul>';
    echo '</div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>';

//Check for error

if (isset($_COOKIE['error'])) {
//Echo error	
	echo '<div align="center" class="alert alert-'.$_COOKIE['errortype'].' alert-dismissible" role="alert">'.$_COOKIE['error'].'</div>';
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

//redir()

function redir($location)
{
    header( "Location: ".$location);
	exit;
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
if (getcwd() == '/var/www/civ/api') {
    //Clean the echo'd stuff in the file
    ob_end_clean();
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
    if ($logAPIRequests) {
        $query = "INSERT INTO api (token, loc, time, page) VALUES (?, ?, NOW(), ?);";
        $stmt = mysqli_stmt_init($con);
        $stmt->prepare($query);
        $stmt->bind_param('sss', $_GET['token'], $_SERVER['REMOTE_ADDR'], basename($_SERVER['PHP_SELF']));
        $stmt->execute();
        $result2=$stmt->get_result();
    }
}

?>