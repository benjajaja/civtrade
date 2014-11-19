<?php
    require ('/var/www/civ/other/req.php');
    $query = "SELECT location, COUNT(`location`) AS amount FROM offers GROUP BY location";
    $result = mysqli_stmt_init($con);
    $result->prepare($query);
    $result->execute();
    $result = $result->get_result();
    $r = array();
    $index = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $rTemp = array("name" => $row['location'], "amount" => $row['amount']);
        array_push($r, $rTemp);
    }
    echo json_encode($r);
?>