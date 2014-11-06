<?php
require('/var/www/civ/other/req.php');

//Only let logged in users view this page
if (!isset($_COOKIE['user'])) {
    redir('login.php');
}

//Create new post

//If unverified

echo '<script>function copyToClipboard() {
  window.prompt("To copy to the clipboard, press Ctrl+C", "/msg gastriko register " + '.$userInfo['confcode'].');
}</script>';

if ($userInfo['verified'] == 'n') {
    echo '<div class="panelControl panel-danger">
    <div class="panel-heading"><font size="5">Verify your account to let people know it\'s really you</font></div>
        <div class="panel-body">Verifying your account is easy and should take less than 10 seconds. All you have to do is log on to civcraft and type:<br><br>
        
        <div align="center"><b>/msg gastriko register '.$userInfo['confcode'].'</b><br>
        
		<b>Do not share this code!</b> It is also your API key, which is linked to your account and logged.</div><br>
		
        Once you verify your account, this message will go away and your verification status will be shown above all your posts.</div>
		
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
    
//Edit user information

$query = "SELECT * FROM users WHERE name= ?";
$stmt = mysqli_stmt_init($con);
$stmt->prepare($query);
$stmt->bind_param('s', $_COOKIE['user']);
$stmt->execute();
$result2=$stmt->get_result();
$row = mysqli_fetch_assoc($result2);

echo '<div class="panelControl panel-info">
    <div class="panel-heading"><font size="5">View and edit user information (Soon: User-specific settings)</font></div>';
	//User options
	if ($level == 3) {
	echo '<div class="panel-body"><form method="POST" action="../actions/loginLogic.php?type=updateSettings" class="form-inline;">
		<input type="checkbox" checked name="relativetime"> Use relative timestamps</input><br>
		<input type="checkbox" checked name="staticnav"> Static navbar (keep it in the same place instead of always being at the top of the page)</input><br> 
		<input type="checkbox" checked name="closed"> I don\'t care about open-source</input><br>
		<br><button type="submit" class="btn btn-info">Submit</button>
		</form><hr style="border-color:#000000; background-color:#000000; color:#000000;"/><!-- Oh my god so many standards -->';
	}
	echo '<form method="POST" class="form-inline;" action="../actions/loginLogic.php?type=changepw"> <div class="form-group"> 
		<div class="form-group"><input type="password" class="form-control" name="newPass" placeholder="New password"></div> 
		<div class="form-group"><input type="password" class="form-control" name="newPassConf" placeholder="Confirm"></div> 
		<button type="submit" class="btn btn-info">Submit</button></form></div></div></div></div>';

//API

if ($row['verified'] == 'y' and $level == 3) {
echo '<div class="panelControl panel-primary">Currently in public beta, requires a verified account.
	<div class="panel-heading"><font size="5">API information</font></div>
	<div class="panel-body"><b>Your API code: '.$row['confcode'].'</b><br>
	All API requests are logged and require your API code. Append them to the API url with ?token='.$row['confcode'].'<br><br>
	All APIs currently:<br>
	<a href="http://'.$url.'/api/cities.php?token='.$row['confcode'].'">http://'.$url.'/api/cities.php</a> returns a list of cities and the total number of offers available';
}
?>