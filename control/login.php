<?php
require('/var/www/civ/other/req.php');

//Only let people who aren't logged in to login
if (isset($_COOKIE['user'])) {
    errorOut("You are already logged in", "info");
}

//Login forms. Nothing special
else {
    echo '<div class="panel panel-primary">
            <div class="panel-heading">Already have an account? Login here</div>
            <div style="padding:1%"><form method="post" action="/actions/loginLogic.php?type=login"> <br>
                <input type="text" name="user" class="form-control" placeholder="Minecraft username"> <br>
                <input type="password" name="pass" class="form-control" placeholder="Password"> <br>
                <button type="submit" class="btn btn-primary">Login</button> 
            </form>
            Forgot your password? Hop on to CivCraft and run the command <b>/msg gastriko register resetpw</b>
        </div></div>
        
        <div class="panel panel-info">
            <div class="panel-heading">Don\'t have an account yet? Sign up here</div>
            <div style="padding:1%"><form method="POST" action="/actions/loginLogic.php?type=signup">
                <input type="text" name="user" class="form-control" placeholder="Minecraft username"> <br>
				Your password must be at least 8 characters long.
                <input type="password" name="pass" class="form-control" placeholder="Password"><br>
                <input type="password" name="passConfirm" class="form-control" placeholder="Password (confirm)"><br>
                <button type="submit" class="btn btn-info">Signup</button>
            </form>
        </div></div>';
}
?>