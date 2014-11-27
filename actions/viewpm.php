<?php
require('/var/www/civ/other/req.php');
//Gather user's PMs
$query = "SELECT * FROM pms WHERE sender LIKE ? OR receiver LIKE ? ORDER BY id DESC";
$stmt = mysqli_stmt_init($con);
$stmt->prepare($query);
$stmt->bind_param('ss', $u, $u);
$stmt->execute();
$result2=$stmt->get_result();

//Show a message if they have none so they know why the page is empty
if(mysqli_num_rows($result2) == 0) { alert("You don't have any PMs, try sending one!", "info"); }

//Set sendTo
if(isset($_GET['to'])) { $sendTo = $_GET['to']; }
else { $sendTo = ''; }

//Send a PM
echo '<div class="panelControl panel-primary">
    <div class="panel-heading"><font size="5">Send a PM</font></div><div class="panel-body">
	<form method="POST" class="form-inline;" action="../actions/sendpm.php"> <div class="form-group"> 
        <div class="input-group">
          <span class="input-group-addon" style="min-width:85px;">Send as</span>
          <input type="text" class="form-control" readonly value="'.$u.'">
        </div><br>
		<div class="input-group">
          <span class="input-group-addon" style="min-width:85px;">Send to</span>
          <input type="text" class="form-control" name="to" placeholder="Username, case insensitive" value="'.$sendTo.'">
        </div><br>
        <div class="input-group">
          <span class="input-group-addon" style="min-width:85px;">Message</span>
          <textarea class="form-control" rows="5" name="message" placeholder="All HTML tags are automatically removed"></textarea>
        </div><br>
		<button type="submit" class="btn btn-primary" style="min-width:85px;">Submit</button>
    </form></div></div></div>';
        
while ($row = $result2->fetch_assoc()) {
    //Primary if they're the sender, info if they're the receiver
    if ($row['sender'] == $u) { echo '<div class="panelControl panel-primary">'; }
    else { echo '<div class="panelControl panel-info">'; }
    
    //Set unread
    if ($row['unread'] == 1) { $unreadText = " - <b>Unread</b>"; }
    else { $unreadText = ""; }
    echo '<div class="panel-heading"><font size="5">'.$row['sender'].' -> '.$row['receiver'].' (ID '.$row['id'].')'.$unreadText.'</font></div><div class="panel-body"><b>Sent '.$row['time'].'</b><br>'.$row['message'].'</div></div><br>';
}

//Mark PMs as read

$query = "UPDATE pms SET unread = 0 WHERE sender = ? OR receiver LIKE ? ORDER BY id DESC";
$stmt = mysqli_stmt_init($con);
$stmt->prepare($query);
$stmt->bind_param('ss', $u, $u);
$stmt->execute();
?>