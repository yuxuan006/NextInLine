<?php

require("function.php"); 

if(!isLoggedIn())
{
    if (isset($_POST['loginButton'])) {
		if (!isset($_POST['username'])) {
			print("Error: The username field was not set.");
		} else if (!isset($_POST['password'])) {
			print("Error: The password field was not set.");
		}

                $username = $_POST['username'];
                $password = $_POST['password'];

                $passwordHash = hash("sha512", $_POST['password']);

                $query = mysql_query("SELECT `user_id` FROM `nxline_user` WHERE `user_name` = '" . mysql_real_escape_string($username) . "' AND `user_passwd` = '" . mysql_real_escape_string($passwordHash) . "' LIMIT 1");

		if (mysql_num_rows($query)) { // The user name and email address are correct
                $sessID = mysql_real_escape_string(session_id());
                $hash = mysql_real_escape_string(hash("sha512", $sessID.$_SERVER['HTTP_USER_AGENT']));

                $userData = mysql_fetch_assoc($query);

    //            $expires = time() + (15);
                mysql_query("DELETE FROM nxline_sessions WHERE user_id =" .(int) $userData['user_id']);

                mysql_query("INSERT INTO `nxline_sessions` (`user_id`, `session_id`, `hash`) VALUES (" .(int) $userData['user_id'].", '" . $sessID. "', '" . $hash. "')");
                
                echo'<script> window.location="index.php"; </script> ';                  
		} 
                else 
                {
                    print("Username and password are not matched!");;
		}
    } 
}
else
{
    echo'<script> window.location="index.php"; </script> ';
}

    echo'<div id="Lgin">
            <form id="LginForm" method="post" action="./login.php">
                <table class = "table" id = "LginTable">
                    <tr>
                        <th colspan ="2" align="center">Please Login!</th>
                    </tr>
                    <tr>
                        <td>Username: </td>
                        <td><input type="text" name="username" id="username"/></td>
                    </tr>
                        <td>Password: </td>
                        <td><input type="password" name="password" id="password"/></td>
                    <tr>
                        <td colspan ="2" align="center"><button type="submit" name="loginButton" id="loginButton">Login</button></td>
                    </tr>
                </table>
            </form>
        </div>';
