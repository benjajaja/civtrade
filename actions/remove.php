<?php
    require('/var/www/civbeta/other/req.php');
    //If they're logged in
    if (isset($_COOKIE['user']))
    {
        //If the ID is numeric
        if (is_numeric($_GET['id'])) {
            //Logic for marking
            if ($_GET['type'] == "mark") {
				$query = "SELECT poster FROM offers WHERE offerid= ?";
				$stmt = mysqli_stmt_init($con);
				$stmt->prepare($query);
				$stmt->bind_param('i', $_GET['id']);
				$stmt->execute();
				$result2=$stmt->get_result();
                if ($level >= 2 or $_COOKIE['user'] == mysqli_fetch_row($result2)[0]) { //Check to make sure user is allowed to preform this action
					$query = "UPDATE offers SET active='n' WHERE offerid= ?";
					$stmt = mysqli_stmt_init($con);
					$stmt->prepare($query);
					$stmt->bind_param('i', $_GET['id']);
					$stmt->execute();
                    errorOut("Successfully marked post as inactive. To view your inactive posts, click \"Show your inactive posts\"", "success");
                }
                else {
                    errorOut("You do not have the required permission MARK_OTHERS_INACTIVE to do that!", "danger");
                }
            }
            
            //Logic for deleting
            else if ($_GET['type'] == 'delete')
            {
                if ($level == 3) {
					$query = "DELETE FROM offers WHERE offerid= ?";
					$stmt = mysqli_stmt_init($con);
					$stmt->prepare($query);
					$stmt->bind_param('i', $_GET['id']);
					$stmt->execute();
                    errorOut("Successfully deleted post", "success");
                }
                else {
                    errorOut("You do not have the required permission DELETE_POST to do that!", "danger");
                }
            }
            
            //Logic for re-activating
            
            else if ($_GET['type'] = 'activate') {
				$query = "SELECT poster FROM offers WHERE offerid= ?";
				$stmt = mysqli_stmt_init($con);
				$stmt->prepare($query);
				$stmt->bind_param('i', $_GET['id']);
				$stmt->execute();
				$result2=$stmt->get_result();
                if ($level >= 2 or $_COOKIE['user'] == mysqli_fetch_row($result2)[0]) { //Check to make sure user is allowed to preform this action
					$query = "UPDATE offers SET active='y' WHERE offerid= ?";
					$stmt = mysqli_stmt_init($con);
					$stmt->prepare($query);
					$stmt->bind_param('i', $_GET['id']);
					$stmt->execute();
                    errorOut("Successfully marked post as active", "success");
                }
                else {
                    errorOut("You do not have the required permission MARK_OTHERS_ACTIVE to do that!", "danger");
                }
            }
            
            //Otherwise
            else {
                errorOut("Something went wrong, please try again", "danger");
            }
        }
        
        //This is if the item ID isn't numeric
        else {
            errorOut("Something went wrong, please try again", "danger");
        }
    }
?>