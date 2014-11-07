<?php
    require ('/var/www/civbeta/other/req.php');
    //Placeholders
    echo '<div align="center" class="alert alert-danger alert-dismissible" role="alert">PMs are in a <b>very early beta</b> and the system is <b>incredibly glitchy</b>. You should probably stick to redditmail for now unless you want to help test.</div>';
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
    
    //If no PMs
    //if (empty($resultAssoc)) { echo '<div align="center" class="alert alert-danger alert-dismissible" role="alert">You don\'t have any PMs yet!</div>'; }
    //Send PM
    echo '<div class="panelControl panel-primary">Although admins can\'t read your PMs on-site, they\'re stored in plaintext in a database that superadmins can access.
    <div class="panel-heading"><font size="5">Compose a new private message</font></div>';
    echo '<div class="panel-body"><form method="POST" class="form-inline;" action="../actions/sendpm.php"> <div class="form-group">
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
        Currently, replying to PMs isn\'t supported, you have to make a whole new thread. This should be fixed soon, but for now make sure you include a method of contact so you can reply easily.
    </div></div></div>';
    
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