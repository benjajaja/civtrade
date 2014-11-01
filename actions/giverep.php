<?php
require('/var/www/civ/other/req.php');
//No duplicate rep
$exists = false;
$query = "SELECT * FROM rep WHERE giver LIKE '".$_COOKIE['user']."';";
$stmt = mysqli_stmt_init($con);
$stmt->prepare($query);
$stmt->bind_param('s', $_COOKIE['user']);
$stmt->execute();
$my = $stmt->get_result();
while ($row = mysqli_fetch_assoc($my)) {
    if ($row['reciever'] == $_POST['user']) {
        $exists = true;
        die(errorOut("You have already given that user rep", "danger"));
    }
}
if ($exists == false) {
    //Make sure reason is filled in
    if (strlen($_POST['reason']) >= 20)
    {
        //Make sure you don't give yourself rep
        if ($_POST['user'] != $_COOKIE['user'])
        {
            //Give rep
            $query = "UPDATE users SET rep = rep + 1 WHERE name= ?";
            $stmt = mysqli_stmt_init($con);
            $stmt->prepare($query);
            $stmt->bind_param('s', $_POST['user']);
            $stmt->execute();
            
            //$query = "INSERT INTO rep (giver,reciever,reason,time) VALUES ('".$_COOKIE['user']."', '".$_POST['user']."', ?, NOW())";
            $query = "INSERT INTO rep (giver,reciever,reason,time) VALUES (?, ?, ?, NOW())";
            
            $stmt = mysqli_stmt_init($con);
            mysqli_stmt_prepare($stmt,$query);
            mysqli_stmt_bind_param($stmt,"sss", $_COOKIE['user'], $_POST['user'], $_POST['reason']);
            mysqli_stmt_execute($stmt);
            errorOut("Successfully gave ".$_POST['user']." reputation!", "success");
        }
        //No giving yourself rep
        else {
            errorOut("You cannot give yourself rep", "danger");
        }
    }
    else {
        errorOut("Reason must be at least 20 characters", "danger");
    }
}
?>