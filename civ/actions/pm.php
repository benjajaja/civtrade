<?php
    require ('/var/www/civ/other/req.php');
	if (!isset($_COOKIE['user'])) {
		errorOut("You must be logged in to use PMs", "danger", "/control/login.php");
	}
    //Placeholders
    if (isset($_GET['to'])) { $toPlace = $_GET['to']; }
    else { $toPlace = ''; }
    
    if (isset($_GET['postID'])) { $idPlace = $_GET['postID']; }
    else { $idPlace = ''; }
    
    //Query for PMs
	$query = ("SELECT * FROM pms WHERE receiver = ? ORDER BY id DESC");
    $stmt = mysqli_stmt_init($con);
    $stmt->prepare($query);
    $stmt->bind_param('s', $_COOKIE['user']);
    $stmt->execute();
    $result=$stmt->get_result();
    $resultAssoc = mysqli_fetch_assoc($result);
	
    //Send PM
    echo '<div class="panelControl panel-primary"><div class="panel-heading"><font size="5">Compose a new private message</font></div>
    <div class="panel-body"><form method="POST" class="form-inline;" action="../actions/sendpm.php"> <div class="form-group">
        <div class="input-group"><span class="input-group-addon">Send as</span>
        <input type="text" class="form-control" disabled value="'.$_COOKIE['user'].'"></div><br>
        
        <div class="input-group"><span class="input-group-addon">Send to</span>
        <input type="text" class="form-control" name="to" value="'.$toPlace.'"></div><br>
        
        <div class="input-group"><span class="input-group-addon">Post ID</span>
        <input type="number" class="form-control" name="postID" value="'.$idPlace.'"></div><br>
		
		<div class="input-group"><span class="input-group-addon">Message</span>
        <textarea class="form-control" rows="5" name="comment"></textarea></div><br>
        <button type="submit" class="btn btn-primary">Submit</button>
        </form><br>
    </div></div></div>';
    //If no PMs
    if (empty($resultAssoc)) { echo '<div style="width:95%;" align="center" class="center-block alert alert-danger alert-dismissible" role="alert">You don\'t have any PMs yet!</div>'; }
    
    //Echo PMs
    while ($row = mysqli_fetch_assoc($result)) {
        //Set post ID
        if ($row['post'] !== NULL) { $post = ' in regards to <a href="http://'.$url.'/?id='.$row['post'].'">post ID '.$row['post'].'</a>'; }
        else { $post = ''; }
        //Echo each panel
        echo '<div class="panelControl panel-info">Sent '.$row['time'].' to '.$row['receiver'].$post.'<div class="panel-heading panel-info"><font size="5">From: '.$row['sender'].'</font></div>
        <div class="panel-body">'.$row['message'].'</div></div>';
    }
?>