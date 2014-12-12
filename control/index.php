<?php
require('/var/www/civ/other/req.php');

//Only let logged in users view this page
if (!isset($_COOKIE['user'])) {
    errorOut('You must be logged in to view this page', 'danger', '/control/login.php');
}

//If unverified

if ($userInfo['verified'] == 'n') {
    echo '<div class="panelControl panel-danger">
    <div class="panel-heading"><font size="5">Verify your account to let people know it\'s really you</font></div>
        <div class="panel-body">Verifying your account is easy and should take less than 10 seconds. All you have to do is log on to civcraft and type:<br><br>
        
        <div align="center"><b>/msg gastriko register '.$userInfo['confcode'].'</b><br>
        
		<b>Do not share this code!</b> It is also your API key, which is linked to your account and logged.</div><br>
		
        Once you verify your account, this message will go away and your verification status will be shown above all your posts.</div>
		
    </div>';
}

//Edit post
if (isset($_GET['edit'])) {
    //Check if editing is allowed
    if (!$allowEdit) { errorOut("Editing is disabled", "danger", "/control"); }
    else {
        //Execute query
        $query = "SELECT * FROM offers WHERE offerid=?";
        $stmt = mysqli_stmt_init($con);
        $stmt->prepare($query);
        $stmt->bind_param('i', $_GET['pid']);
        $stmt->execute();
        $row=mysqli_fetch_assoc($stmt->get_result());
        
        //Check if they're allowed to edit this post
        if (strlen($row['lastbidder']) != 0) { errorOut("You cannot edit an auction", "danger"); }
        else if ($row['poster'] != $u and $level != 3) { errorOut("You can only edit your own posts", "danger"); }
        
        //Set values
        else { $valHave = $row['have']; $amtHave = $row['haveamt']; $amtWant = $row['wantamt']; $valWant = $row['want']; $valLoc = $row['location']; $valNotes = $row['notes']; $out = "../actions/editpost.php?pid=".$_GET['pid']; }
    }
}
else {
    //Set empty values
	$valHave = ''; $amtHave = ''; $amtWant = ''; $valWant = ''; $valLoc = ''; $valNotes = ''; $out = "../actions/submitlisting.php";
}

//New post
echo '<div class="panelControl panel-primary">
<div class="panel-heading"><font size="5">Create new post</font></div>';
//Echo inputs
echo '<div class="panel-body"><form method="POST" class="form-inline;" action="'.$out.'"> <div class="form-group">
    <b>I have...</b> <div class="form-group"><input value="'.$amtHave.'" type="number" class="form-control" name="amountHave" placeholder="Amount - If you don\'t know the amount, just enter 0 and it will be replaced with \'???\' (not auctions)"></div> 
	<div class="form-group"><input value="'.$valHave.'" type="text" class="form-control" name="have" placeholder="Item name"></div>
	<b>I want...</b> <div class="form-group"><input value="'.$amtWant.'" type="number" class="form-control" name="amountWant" placeholder="Amount - If you don\'t know the amount, just enter 0 and it will be replaced with \'???\' (not auctions). If auction, this is the starting value."></div> 
	<div class="form-group"><input type="text" value="'.$valWant.'" class="form-control" name="want" placeholder="Item name"></div>
	<b>I live in (do not abbreviate)...</b> <div class="form-group"><input value="'.$valLoc.'" type="text" class="form-control" name="loc" placeholder="Nearest city';
    if ($cityCheckType == "force") { echo ' - Must match a city on Txapu.'; } //Warn if the city type is forced
    echo '"></div>
	<b>Notes (optional)...</b> <div class="form-group"><input value="'.$valNotes.'" type="text" class="form-control" name="notes" placeholder="PM me on reddit to discuss, I\'m /u/'.$_COOKIE['user'].'."></div>';
	//Auction (which can only show if not editing because you can't edit auctions)
    if (!isset($_GET['edit'])) { 
		echo '<button type="submit" class="btn btn-primary">Submit</button><br><br>
        <b>Or, make this an auction! Minimum increase...</b> <div class="form-group"><input type="number" class="form-control" name="mininc" placeholder="Amount"></div>
        <button type="submit" class="btn btn-primary">Create auction</button>'; 
    }
    //Edit post button
	else { echo '<button type="submit" class="btn btn-primary">Edit post</button>'; }
	echo '</div></div></div></form>';
    
//Get checked values
if ($userInfo['closed'] == 1) { $closed = 'checked'; } else { $closed = ''; }
if ($userInfo['staticnav'] == 1) { $staticNav = 'checked'; } else { $staticNav = ''; }
//Change user settings
	echo '<div class="panelControl panel-info"><div class="panel-heading"><font size="5">Change Settings</font></div><div class="panel-body">
	<form method="POST" action="../actions/loginLogic.php?type=updateSettings" class="form-inline;">
	<input type="checkbox" name="staticnav" '.$staticNav.'> Locked navbar (keep it in the same place instead of always being at the top of the page)</input><br> 
	<input type="checkbox" name="closed" '.$closed.'> I don\'t care about open source</input><br>
	<br><button type="submit" class="btn btn-info">Submit</button>
	</form></div></div></div>';
echo '<div class="panelControl panel-primary">
    <div class="panel-heading"><font size="5">Change Password</font></div><div class="panel-body">';
	//User options
	echo '<form method="POST" class="form-inline;" action="../actions/loginLogic.php?type=changepw"> <div class="form-group"> 
		<div class="form-group"><input type="password" class="form-control" name="newPass" placeholder="New password"></div> 
		<div class="form-group"><input type="password" class="form-control" name="newPassConf" placeholder="Confirm"></div> 
		<button type="submit" class="btn btn-primary">Submit</button></form></div></div></div>';

//API
if ($userInfo['verified'] == 'y') {
echo '<div class="panelControl panel-info">
	<div class="panel-heading"><font size="5">API</font></div>
	<div class="panel-body">Your API code: '.$userInfo['confcode'].'<b> - Do not share this code! It is linked to your account and logged</b><br>
	All API requests are logged and require your API code. Append them to the API url with ?token='.$userInfo['confcode'].'<br><br>
	All APIs currently:<br>
	<a href="http://'.$url.'/api/cities.php?token='.$userInfo['confcode'].'">http://'.$url.'/api/cities.php</a> returns a list of cities and the total number of offers available<br>
	<a href="http://'.$url.'/api/userPosts.php?token='.$userInfo['confcode'].'">http://'.$url.'/api/userPosts.php</a> returns a list of users, along with their total posts<br><br>
    <a href="https://www.reddit.com/message/compose/?to=minicl55&subject=API%20request">Request a new API!</a> I\'ll try my best to make any API needed.';
}
?>