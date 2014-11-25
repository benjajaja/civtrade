<?php
    require ('/var/www/civ/other/req.php');
    $query = "SELECT poster,COUNT(`poster`) AS totalPosts FROM offers GROUP BY poster ORDER BY totalPosts DESC";
    $result = mysqli_stmt_init($con);
    $result->prepare($query);
    $result->execute();
    $result = $result->get_result();
    $r = array();
    $index = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $rTemp = array("name" => $row['poster'], "amount" => $row['totalPosts']);
        array_push($r, $rTemp);
    }
    echo json_encode($r);
?>