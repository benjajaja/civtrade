<?php
	require('/var/www/civ/other/req.php');
	
	//Search
	if (!isset($_GET['id'])) {
		echo '<div class="panel panel-primary">
			<div class="panel-heading"><font size="5">Search - Fill in as little or as much as you want</font></div><br>
			<div align="center" class="form-group">
			<form class="form-inline" method="GET" action="/">
			<input type="text" name="want" class="form-control" placeholder="I want">
			<input type="text" name="have" class="form-control" placeholder="I have">
			<input type="text" name="loc" class="form-control" placeholder="Location (full city name)">
			<button type="submit" class="btn btn-default">Search</button></form>';
			//If logged in, allow users to view this disabled posts
            if (isset($_COOKIE['user'])) { 
				if ($level == 3) {
					echo '<a href="../?showAllDisabled"><button class="btn btn-info">Show all inactive posts</button></a> ';
				}
				echo '<a href="../?showOwnDisabled"><button class="btn btn-primary">Show your inactive posts</button></a>';
			}
            if (!isset($_GET['sort'])) { echo '<br><a href="../?sort=newest">Order posts by newest first</a>'; }
            else { echo '<br><a href="../">Order posts by default</a>'; }
		echo '</div></div></div>';
	}
	
	//Logic for ID
	if (isset($_GET['id'])) {
        alert('You have been linked directly to post ID '.$_GET['id'].'. <a href="/">View all offers</a>.', 'info');
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
				$query = "SELECT * FROM offers WHERE active='n' ORDER BY lastbumped DESC";
			}
			else {
				errorOut('You do not have permission to view this page', "danger");
			}
		}
		
		//Show own disabled
		else if (isset($_GET['showOwnDisabled'])) {
			if (isset($_COOKIE['user'])) { $query = "SELECT * FROM offers WHERE active='n' AND poster=? ORDER BY offerid DESC"; }
			else {
				errorOut('You must login to view your own disabled posts', 'danger');
			}
		}
		
		//Generic query
		else {
			if (isset($_GET['sort'])) { $query = "SELECT * FROM offers WHERE active='y' ORDER BY offerid DESC"; }
			else { $query = "SELECT * FROM offers WHERE active='y' ORDER BY lastbumped DESC"; }
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
			$queryBuilder .= " AND `have` LIKE ?";
			$toBind1 = strip_tags('%'.rtrim($_GET['want'], 's').'%');
		}
		else {
			$queryBuilder .= " AND 'have' = ?";
			$toBind1 = 'have';
		}
		if ($_GET['have'] != '') {
			$queryBuilder .= " AND `want` LIKE ?";
			$toBind2 = strip_tags('%'.rtrim($_GET['have'], 's').'%');
		}
		else {
			$queryBuilder .= " AND 'have' = ?";
			$toBind2 = 'have';
		}
		if ($_GET['loc'] != '') {
			$queryBuilder .= " AND `location` LIKE ?";
			$toBind3 = strip_tags('%'.rtrim($_GET['loc'], 's').'%');
		}
		else {
			$queryBuilder .= " AND 'have' = ?";
			$toBind3 = 'have';
		}
		
		//Anti SQLi
		if (isset($_GET['sort'])) { $query = $queryBuilder." AND active='y' ORDER BY offerid DESC"; }
		else { $query = $queryBuilder." AND active='y' ORDER BY lastbumped DESC"; }
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

		if ($posterInfo[$row["poster"]] == 'y') {
			$verifiedText = 'Verified';
		}
		else {
			$verifiedText = '<b>Unverified</b>';
		}
		
		//If it's disabled, make it red and put a bold "DISABLED" thing
		if (!isset($_GET['showAllDisabled']) and !isset($_GET['showOwnDisabled'])) {
		  echo '<div class="panel panel-'.$currentVersion.'">';
		}
		else {
			echo '<div class="panel panel-danger">';
		}
			$postedTime = strtotime($row['creation']);
			$diff = time() - $postedTime;
			if (date("d", $diff) == 1) { $dayString = 'day'; }
			else { $dayString = 'days'; }
			
			if (date("H", $diff) == 1) { $hourString = 'hour'; }
			else { $hourString = 'hours'; }
			
			if ($timestamps) {
				//Hour:Minute Month/day/year
				echo 'Offer ID: '.$row['offerid'].', posted '.date("H:i m/d/y", strtotime($row['creation']));
			}

			//Warn if edited
			if ($warnEdits and $row['lastedited'] != '') {
				echo '<b>Last edited: </b> '.date("m/d/y - H:i", strtotime($row['lastedited']));
			}
			
			if ($row['aucinc'] !== null) { 
				$aucText = ' - <b>Auction</b>'; 
				$aucVal = '<b>Current bid:</b>';
			}
			else {
				$aucText = ''; 
				$aucVal = '<b>Wants:</b>';
			}
			
			echo '<div class="panel-heading"><font size="5">'.$row['poster'].' ('.$verifiedText.')'.$aucText.'</font></div>
			<div class="panel-body">';
			
			//Replace 0 with ???
			if ($row['haveamt'] == 0) { echo '<b>Has:</b> ??? '.$row['have'].'<br>'; }
			else { echo '<b>Has:</b> '.$row['haveamt'].' '.$row['have'].'<br>'; }
			if ($row['wantamt'] == 0) { echo $aucVal.' ??? '.$row['want'].'<br>'; }
			else { echo $aucVal.' '.$row['wantamt'].' '.$row['want'].'<br>'; }
			
			//Echo out location and notes
			echo '<b>Location:</b> '.$row['location'].'<br>';
			//echo '<b>Location:</b><br>
			echo '<b>Notes:</b> '.$row['notes'].'<br>';
			//Auction info
			if ($row['aucinc'] !== null) {
				//echo '<b>Minimum increase:</b> '.$row['aucinc'].'<br>';
				if ($posterInfo[$row["lastbidder"]] == 'n') { $bidderUnv = " - <b>Unverified</b>"; }
				else { $bidderUnv = ' - Verified'; }
				echo '<b>Last bidder:</b> '.$row['lastbidder'].$bidderUnv.'<br>';
			}
			
			//Link directly to the post
			
			echo '<a href="http://'.$url.'/?id='.$row['offerid'].'"><button type="button" class="btn btn-info">Direct link</button></a> ';       
			
			//CivcraftExchange
			$redditPost = 'https://www.reddit.com/r/CivcraftExchange/submit?selftext=true&title=[H]%20'.$row['have'].'%20[W]%20'.$row['want'].'&text=I%20live%20in%20'.$row['location'].'%0A%0D%0AI%20have%20'.$row['haveamt'].'%20'.$row['have'].'%20and%20'.$row['wantamt'].'%20'.$row['want'].'.%0D%0A%0D%0A'.$row['notes'].'%0A%0D%0A----%0A%0D%0A View%20this%20post%20on%20[CivTrade](http://civtrade.com/?id='.$row['offerid'].')';
			
			//Check if they can bump
			$now = time();
			$dbDate = strtotime($row['lastbumped']);
			
			//If they're logged in...
			if (isset($_COOKIE['user'])) {
				//If auction, increase last bid
				if ($row['aucinc'] !== null and $row['poster'] != $_COOKIE['user'] and $row['lastbidder'] != $_COOKIE['user']) { echo '<a button type="button" class="btn btn-primary" href="http://'.$url.'/actions/incAuc.php?id='.$row['offerid'].'">Increase bid by '.$row['aucinc'].' '.$row['want'].'</a> '; }
                //Send PM to highest bidder
                if ($row['poster'] == $u and $row['lastbidder'] != $u and !is_null($row['aucinc'])) { echo ' <a href="./actions/viewpm.php?to='.$row['lastbidder'].'"><button type="button" class="btn btn-primary">Send highest bidder a PM</button></a> '; }
				//Send PM
				if ($row['poster'] != $_COOKIE['user']) { echo ' <a href="./actions/viewpm.php?to='.$row['poster'].'"><button type="button" class="btn btn-primary">Send user a PM</button></a> '; }
				//If they ARE the poster, allow them to post directly to /r/civcraftexchange
				if ($directPost and $row['poster'] == $_COOKIE['user']) { echo ' <a href="'.$redditPost.'" button type="button" class="btn btn-primary">Post to /r/CivcraftExchange</button></a> '; }
				//If they're an admin OR they're the poster, allow them to deactivate it
				if ($_COOKIE['user'] == $row['poster'] or ($level == 3 and isset($_GET['id'])) and $row['active'] == 'y') { echo '<a href="./actions/remove.php?type=mark&id='.$row['offerid'].'"><button type="button" class="btn btn-warning">Mark inactive</button></a> '; }
				//If they ARE the poster, allow them to edit the post
				if ((($u == $row['poster'] or ($level == 3 and isset($_GET['id']))) and $row['lastbidder'] == '') and $allowEdit) { echo ' <a href="http://'.$url.'/control/?edit&pid='.$row['offerid'].'" button type="button" class="btn btn-success">Edit post</button></a> '; }
				//Allow users to bump their posts
				if (($u == $row['poster'] or ($level == 3 and isset($_GET['id']))) and ($now > $dbDate + 86400)) { echo '<a href="./actions/bump.php?pid='.$row['offerid'].'"><button type="button" class="btn btn-danger">Bump</button></a> '; }
				//If it's me and this is a direct link, delete it
				if ($level == 3 and isset($_GET['id'])) { echo '<a href="./actions/remove.php?type=delete&id='.$row['offerid'].'"><button type="button" class="btn btn-danger">Delete</button></a>'; }
				//If viewing disabled posts, show a "mark active" button
				if (isset($_GET['showAllDisabled']) or isset($_GET['showOwnDisabled'])) { echo '<a href="./actions/remove.php?type=activate&id='.$row['offerid'].'"> <button type="button" class="btn btn-warning">Mark active</button></a>'; }
			}
		  echo '</div>
		</div>';
	}
?>