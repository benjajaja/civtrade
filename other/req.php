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
$shouldFramebreak = false;

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
//Set this to false if you do not want to log API requests. Requires requireAPIToken to be true
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
//Default: info
$newsType = "info";

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

//forceStaticOnMobile
//If true, forces the navbar to be static if the user is running a mobile device. There is no way for the user to disable this
//Default: true
$forceStaticOnMobile = true;

//truncateIP
//If true, remove the last subset from the logged IPs. For example, 127.0.0.1 would turn in to 127.0.0
//Default: true
$truncateIP = true;

//allowEdit
//If true, allows users to edit their own posts. You cannot edit an auction
//Default: true
$allowEdit = true;

//warnEdits
//If true, warn that a post has been edited
//Default: True
$warnEdits = true;

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
        setcookie("user", "", time()-360000, "/", $url, null, true);
        setcookie("userID", "", time()-3600000, "/", $url, null, true);
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

//Source: http://detectmobilebrowsers.com/
$useragent=$_SERVER['HTTP_USER_AGENT'];
if ($forceStaticOnMobile and preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
	$staticPass = false;
}
else { 
	$staticPass = true;
}

//echo top
header("Access-Control-Allow-Origin: *"); //the_gipsy wanted this for his API
echo '<link rel="stylesheet" type="text/css" href="http://'.$url.'/other/stylenew.css?59">
<head><title>CivTrade!</title></head>';
if ($userInfo['staticnav'] != 1 and $staticPass) { echo '<body class="body">'; }
else { echo '<body class="bodystatic">'; }
echo '<script src="http://code.jquery.com/jquery.js"></script>
<script src="'.$url.'/other/bootstrap.js"></script>
<nav class="navbar';
if ($userInfo['staticnav'] != 1 or $staticPass) { echo ' navbar-fixed-top'; }
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
			echo '<li><a href="/control">Control Panel</a></li>
			<li><a href="/actions/viewpm.php">Private messages</a></li>
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
	setcookie("error", '', time()-360, "/", $url, null, true);
	setcookie("errortype", '', time()-360, "/", $url, null, true);
	unset($_COOKIE['error']);
	unset($_COOKIE['errortype']);
    $err = true;
}

//ErrorOut()

function errorOut($error, $type = "info", $rel = "/")
{
    global $url;
	setcookie("error", $error, time()+86400, "/", $url, null, true);
	setcookie("errortype", $type, time()+86400, "/", $url, null, true);
    header("Location: http://".$url.$rel);
	exit;
}

//alert()

function alert($error, $type = "danger") {
    echo '<div align="center" class="alert alert-'.$type.' alert-dismissible center-block" role="alert" style="width:97%;">'.$error.' <font size="2">(<a href="">clear</a>)</font></div>';
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
    if(preg_match('/(?i)msie [1-8]/',$_SERVER['HTTP_USER_AGENT'])) {
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
        if ($row['verified'] != 'y') { die('Your API token is invalid or your account is not verified'); }
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

$query = "SELECT * FROM pms WHERE receiver = ? AND unread = 1";
$stmt = mysqli_stmt_init($con);
$stmt->prepare($query);
$stmt->bind_param('s', $u);
$stmt->execute();
$result2=$stmt->get_result();
if (mysqli_num_rows($result2) != 0 and $_SERVER['PHP_SELF'] != '/actions/viewpm.php' and !isset($err)) { alert('You have a new PM! <a href="http://'.$url.'/actions/viewpm.php">Click here to view it!</a>', 'info'); }

?>