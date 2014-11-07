<?php
require('/var/www/civ/other/req.php');
date_default_timezone_set("America/Los_Angeles");
//Search

echo '<div class="panel panel-primary">
    <div class="panel-heading"><font size="5">Search - Fill in as little or as much as you want</font></div><br>
    <div align="center" class="form-group">
            <form class="form-inline" method="GET" action="/">
            <input type="text" name="want" class="form-control" placeholder="I want">
            <input type="text" name="have" class="form-control" placeholder="I have">
            <input type="text" name="loc" class="form-control" placeholder="Location (full city name)">
            <button type="submit" class="btn btn-default">Search</button></form>
            <a href="../?showOwnDisabled"><button class="btn btn-primary">Show your inactive posts</button></a>';
			if ($level == 3) {
				echo ' <a href="../?showAllDisabled"><button class="btn btn-info">Show your inactive posts</button></a>';
			}
			echo '</div></div></div>';

//Logic for ID
if (isset($_GET['id'])) {
    $query = ("SELECT * FROM offers WHERE active='y' AND offerid=? ORDER BY offerid DESC");
    $stmt = mysqli_stmt_init($con);
    $stmt->prepare($query);
    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();
    $result=$stmt->get_result();
}

//Logic for if NOT searching
else if (!isset($_GET['want'])) {
    //Show all disabled
	if (isset($_GET['showAllDisabled'])) {
        if ($level >= 2) {
            $query = "SELECT * FROM offers WHERE active='n' ORDER BY offerid DESC";
        }
        else {
            errorOut('You do not have the required permission VIEW_ALL_INACTIVE to view this page', "danger");
        }
	}
    
    //Show own disabled
    else if (isset($_GET['showOwnDisabled'])) {
        if (isset($_COOKIE['user'])) {
        $query = "SELECT * FROM offers WHERE active='n' AND poster=? ORDER BY offerid DESC";
        }
        else {
            errorOut('You must login to view your own disabled posts', 'danger');
        }
    }
    
    //Generic query
	else {
		$query = "SELECT * FROM offers WHERE active='y' ORDER BY offerid DESC";
	}
	$stmt = mysqli_stmt_init($con);
    $stmt->prepare($query);
    if (isset($_GET['showOwnDisabled'])) { $stmt->bind_param('s', $_COOKIE['user']); }
    $stmt->execute();
    $result=$stmt->get_result();
}

//Logic for searching
else
{
    $queryBuilder = "SELECT * FROM offers WHERE 1 = 1 ";
    
    //These all build the query for later
    if ($_GET['want'] != '') {
        $queryBuilder .= " AND `have` = ?";
        $toBind1 = rtrim($_GET['want'], 's');
    }
	else {
		$queryBuilder .= " AND 'have' = ?";
        $toBind1 = 'have';
	}
    if ($_GET['have'] != '') {
        $queryBuilder .= " AND `want` = ?";
        $toBind2 = rtrim($_GET['have'], 's');
    }
	else {
		$queryBuilder .= " AND 'have' = ?";
        $toBind2 = 'have';
	}
    if ($_GET['loc'] != '') {
        $queryBuilder .= " AND `location` = ?";
        $toBind3 = rtrim($_GET['loc'], 's');
    }
	else {
		$queryBuilder .= " AND 'have' = ?";
        $toBind3 = 'have';
	}
    
    //Anti SQLi
    $query = $queryBuilder." AND active='y' ORDER BY offerid DESC";
    $stmt = mysqli_stmt_init($con);
    $stmt->prepare($query);
    $stmt->bind_param('sss', $toBind1, $toBind2, $toBind3);
    $stmt->execute();
    $result=$stmt->get_result();
}

//To swap between rows
$currentVersion = 'primary';

//Verification status 
$query = "SELECT name,verified FROM users";
$stmt = mysqli_stmt_init($con);
$stmt->prepare($query);
$stmt->execute();
$result2=$stmt->get_result();
$posterInfo = array();
while ($row = mysqli_fetch_assoc($result2)) {
  $posterInfo[$row["name"]] = $row["verified"];
}

//Output cards
while ($row = $result->fetch_assoc()) {
    //Swap between styles
    if ($currentVersion == 'primary' or $currentVersion == "danger") {
        $currentVersion = 'info';
    }
    else {
        $currentVersion = 'primary';
    }

    if ($posterInfo[$row["poster"]] == 'n') {
        $verifiedText = '<b>Unverified</b>';
    }
    else {
        $verifiedText = 'Verified';
    }
    
    //If it's disabled, make it red and put a bold "DISABLED" thing
	if (!isset($_GET['showAllDisabled']) and !isset($_GET['showOwnDisabled'])) {
      echo '<div class="panel panel-'.$currentVersion.'">';
	}
	else {
		echo '<div class="panel panel-danger"> <b>DISABLED</b> - ';
	}
        $postedTime = strtotime($row['creation']);
        $diff = time() - $postedTime;
        if (date("d", $diff) == 1) { $dayString = 'day'; }
        else { $dayString = 'days'; }
        
        if (date("H", $diff) == 1) { $hourString = 'hour'; }
        else { $hourString = 'hours'; }
        
        //Hour:Minute Month/day/year
		echo 'Offer ID: '.$row['offerid'].', posted '.date("H:i m/d/y", strtotime($row['creation']));
        echo '<div class="panel-heading"><font size="5">'.$row['poster'].' ('.$verifiedText.')</font></div>
        <div class="panel-body">';
		
		//Hyperlink /u/ and /r/
		/*$words[] = explode(" ", $row['have']);
		foreach ($words as $w) {
			if ($w[0] == '/' and ($w[1] == 'r' or $w[1] == 'u') and $w[2] == '/') {
				$w = '<a href="http://reddit.com'.$w.'">'.$w.'</a>';
			}
		}
		implode($words);*/
		
        //Replace 0 with ???
        if ($row['haveamt'] == 0) { echo '<b>Has:</b> ??? '.$row['have'].'<br>'; }
		else { echo '<b>Has:</b> '.$row['haveamt'].' '.$row['have'].'<br>'; }
        if ($row['wantamt'] == 0) { echo '<b>Wants:</b> ??? '.$row['want'].'<br>'; }
        else { echo '<b>Wants:</b> '.$row['wantamt'].' '.$row['want'].'<br>'; }
        
        //Echo out location and notes
        echo '<b>Location:</b> '.$row['location'].'<br>';
        //echo '<b>Location:</b><br>
        echo '<b>Notes:</b> '.$row['notes'].'<br>';
        
        //Link directly to the post
        
        echo '<a href="http://'.$url.'/?id='.$row['offerid'].'"><button type="button" class="btn btn-info">Direct link</button></a> ';
        
        //Send PM
        echo ' <a href="./actions/pm.php?postID='.$row['offerid'].'&to='.$row['poster'].'"><button type="button" class="btn btn-primary">Send user a PM</button></a> '; 
        
        //If they're logged in...
        if (isset($_COOKIE['user'])) {
            //If they're an admin OR they're the poster, allow them to deactivate it
            if (($level >= 2 or $_COOKIE['user'] == $row['poster']) and $row['active'] == 'y') {
                echo '<a href="./actions/remove.php?type=mark&id='.$row['offerid'].'"><button type="button" class="btn btn-warning">Mark inactive</button></a> ';
            }
            
            //If it's me, delete it
            if ($level == 3)
            {
                echo '<a href="./actions/remove.php?type=delete&id='.$row['offerid'].'"><button type="button" class="btn btn-danger">Delete</button></a>';
            }
            
            //If viewing disabled posts, show a "mark active" button
            if (isset($_GET['showAllDisabled']) or isset($_GET['showOwnDisabled'])) {
                echo '<a href="./actions/remove.php?type=activate&id='.$row['offerid'].'"> <button type="button" class="btn btn-warning">Mark active</button></a>';
            }
        }
      echo '</div>
    </div>';
}
//Close the anti SQLi thing
if (isset($_GET['want'])) {
    mysqli_stmt_close($stmt);
}
?>