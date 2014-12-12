<?php
require('/var/www/civ/other/req.php');

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
    //Check that txpau has it as a valid city
    else if (strpos(file_get_contents('/var/cities.geojson'), $_POST['loc']) === false and $cityCheckType == "force") {
        errorOut('Invalid city location. If you think this is wrong, please submit a new city to <a href="http://txapu.com/">Txapu</a>', 'danger', '/control/?edit&pid='.$_GET['pid']);
    }
    else {
        $query = "UPDATE offers SET have=?,haveamt=?,want=?,wantamt=?,notes=?,location=?,lastedited=NOW() WHERE offerid=?";
        $stmt = mysqli_stmt_init($con);
        $stmt->prepare($query);
        //Set variables with stripped tags
        $have = strip_tags($_POST['have']); $haveAmt = strip_tags($_POST['amountHave']); $want = strip_tags($_POST['want']); $wantAmt = strip_tags($_POST['amountWant']); $loc = strip_tags($_POST['loc']); $notes = strip_tags($row['notes']);
        $stmt->bind_param('sisissi', $have, $haveAmt, $want, $wantAmt, $notes, $loc, $_GET['pid']);
        $stmt->execute();
        errorOut("Successfully edited post ID ".$_GET['pid'].'. Please review your changes below', "success", "/?id=".$_GET['pid']);
    }
}
?>