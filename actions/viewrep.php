<?php
require("/var/www/civ/other/req.php");
//If user is set
$shouldQuery = true;
if (isset($_GET['user'])) {
    //Make query WHERE name=
    $query = "SELECT * FROM rep WHERE reciever= ? ORDER BY repid DESC;";
}
//Otherwise generic query
else {
    $query = "SELECT * FROM rep ORDER BY repid DESC;";
}

$stmt = mysqli_stmt_init($con);
$stmt->prepare($query);
if (isset($_GET['user'])) {
    $stmt->bind_param('s', $_GET['user']);
}
$stmt->execute();
$result2=$stmt->get_result();
while ($row = mysqli_fetch_assoc($result2)) {
    echo '<div class="panelControl panel-primary">
        <div class="panel-heading"><font size="4">+1 rep to '.$row['reciever'].' from '.$row['giver'].'</font></div>
            <b>Reason:</b> '.$row['reason'].'<br>
            <b>Time posted:</b> '.$row['time'].'<br>
            <b>Rep ID:</b> '.$row['repid'].'
        </div>';
}
?>