<?php

session_start();

require("config.php");  

function isLoggedIn()
{      
    $sessID = mysql_real_escape_string(session_id());
    $hash = mysql_real_escape_string(hash("sha512", $sessID.$_SERVER['HTTP_USER_AGENT']));
    
    
    $query = mysql_query("SELECT `user_id` FROM `nxline_sessions` WHERE `session_id` = '" . $sessID . "' AND `hash` = '" . $hash . "' LIMIT 1");
    
    if(mysql_num_rows($query))
    {
        $user_id = mysql_fetch_assoc($query);
        return $user_id['user_id'];
    }
    else
    {
        return false;
    }
}

function getUser()
{
    $userLogin = isLoggedIn();
    if($userLogin)
    {
        $query = mysql_query("SELECT * FROM nxline_user WHERE user_id =". (int) $userLogin);
        
        return mysql_fetch_assoc($query);
    }
    else 
     {
        return false;
     }
}
?>
