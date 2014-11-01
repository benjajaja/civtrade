<?php
require('/var/www/civ/other/req.php');

//Only let logged in users view this page
if (!isset($_COOKIE['user'])) {
    redir('login.php');
}
/*
if ($_COOKIE['user'] == 'dx_dt' or $_COOKIE['user'] == 'minicl55') {
	 echo '<div class="panelControl panel-warning">
    <div class="panel-heading"><font size="5">The following files are avaible for you to download:</font></div>
        <a href="./spigot.jar">Spigot.jar</a><br>
    </div>';
}*/

//Create new post

//Gather user info
$query = "SELECT * FROM users WHERE name= ?";
$stmt = mysqli_stmt_init($con);
$stmt->prepare($query);
$stmt->bind_param('s', $_COOKIE['user']);
$stmt->execute();
$result2=$stmt->get_result();
$userInfo = mysqli_fetch_assoc($result2);

//If unverified

echo '<script>function copyToClipboard(text) {
  window.prompt("To copy to the clipboard, press Ctrl+C", "/msg gastriko register " + '.$userInfo['confcode'].');
}</script>';

if ($userInfo['verified'] == 'n') {
    echo '<div class="panelControl panel-danger">
    <div class="panel-heading"><font size="5">Verify your account to let people know it\'s really you</font></div>
        Verifying your account is easy and should take less than 10 seconds. All you have to do is log on to civcraft and type:<br><br>
        
        <div align="center"><b>/msg gastriko register '.$userInfo['confcode'].'</b> (<a href="javascript:copyToClipboard();">Copy to clipboard</a>)</div><br>
        
        Once you verify your account, this message will go away and your verification status will be shown above all your posts.
    </div>';
}

//New post
echo '<div class="panelControl panel-primary">
<div class="panel-heading"><font size="5">Create new post</font> - New! If you don\'t know the amount, just enter 0 and it will be replaced with \'???\'</div>';

//Echo inputs
echo '<div class="panel-body"><form method="POST" class="form-inline;" action="../actions/submitlisting.php"> <div class="form-group">
	<b>I have...</b> <div class="form-group"><input type="number" class="form-control" name="amountHave" placeholder="1"></div> 
	<div class="form-group"><input type="text" class="form-control" name="have" placeholder="diamond"></div>
	<b>I want...</b> <div class="form-group"><input type="number" class="form-control" name="amountWant" placeholder="16"></div> 
	<div class="form-group"><input type="text" class="form-control" name="want" placeholder="iron"></div>
	<b>I live in (do not abbreviate)...</b> <div class="form-group"><input type="text" class="form-control" name="loc" placeholder="Nearest city - Only include city name, any extra information goes in notes"></div> 
	<b>Notes (optional)...</b> <div class="form-group"><input type="text" class="form-control" name="notes" placeholder="PM me on reddit to discuss, I\'m /u/'.$_COOKIE['user'].'."></div> 
	<button type="submit" class="btn btn-primary">Submit</button>
	</form>
</div></div></div>';
    
   
//Give rep

echo '<div class="panelControl panel-info">
<div class="panel-heading"><font size="5">Give rep to another user</font></div>
<form style="padding:1%" method="POST" class="form-inline;" action="../actions/giverep.php"> <div class="form-group"> 
<div class="form-group"><input type="text" class="form-control" name="user" placeholder="Their username"></div> 
<div class="form-group"><input type="text" class="form-control" name="reason" placeholder="Reason"></div>';
if ($userInfo['verified'] == 'y') {
	echo '<button type="submit" class="btn btn-info">Submit</button></form>';
}
else {
	echo '<button type="submit" class="btn btn-info" disabled>You must be verified to give reputation</button></form>';
}
echo '</div></div>';
   
//Edit user information

$query = "SELECT * FROM users WHERE name= ?";
$stmt = mysqli_stmt_init($con);
$stmt->prepare($query);
$stmt->bind_param('s', $_COOKIE['user']);
$stmt->execute();
$result2=$stmt->get_result();
$row = mysqli_fetch_assoc($result2);

//Get checkbox status
if ($row['relativetime'] == 1) { $useRel = "checked"; } else { $useRel = null; }
if ($row['whitenav'] == 1) { $useWhite = "checked"; } else { $useWhite = null; }
if ($row['defaultsearch'] == 0) { $useDefault = " checked"; } else { $useDefault = null; }
echo '<div class="panelControl panel-primary">
    <div class="panel-heading"><font size="5">View and edit user information</font></div>
        <div style="padding:1%">You are logged in as '.$_COOKIE['user'].' and have <a href="../actions/viewrep.php?user='.$_COOKIE['user'].'">'.$row['rep'].'</a> reputation. <a href="../actions/viewrep.php">View all reputation</a><br>
        <a href="../?showOwnDisabled">Show your disabled posts</a><br>';
        if ($level == 3)
        {
            echo '<a href="../?showAllDisabled">Show all disabled posts</a><br>';
        }
		//User options
		if ($level == 3) {
		echo '<hr style="border-color:#000000; background-color:#000000; color:#000000;"/> <!-- Oh my god so many standards -->';
		echo '<form method="POST" action="../actions/loginLogic.php?type=updateSettings" class="form-inline;">
			<input type="checkbox" checked name="relativetime"> Use relative timestamps</input><br>
			<input type="checkbox" checked name="whtienav"> Use white navbar</input><br> 
			<input type="checkbox"'.$useDefault.' name="defaultsearch"> Put search in navbar (Warning: Ugly and gross)</input><br>
			<br><button type="submit" class="btn btn-info">Submit</button>
			</form><hr style="border-color:#000000; background-color:#000000; color:#000000;"/>';
		}
        echo '<form method="POST" class="form-inline;" action="../actions/loginLogic.php?type=changepw"> <div class="form-group"> 
            <div class="form-group"><input type="password" class="form-control" name="newPass" placeholder="New password"></div> 
            <div class="form-group"><input type="password" class="form-control" name="newPassConf" placeholder="Confirm"></div> 
            <button type="submit" class="btn btn-info">Submit</button></form></div>'; 
    echo '</div></div>';
   
?>