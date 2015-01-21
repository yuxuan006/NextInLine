<?php

require("header.inc.php");

//error_reporting(E_ALL^ E_WARNING);

$result = mysql_query("SELECT * FROM nxline_user WHERE user_id = " . mysql_real_escape_string($_GET['user_id']));

if(!$result)
{
    echo 'The user could not be displayed, please try again later.' . mysql_error();
    
    header("Location: ./changePassword.php?user_id=0");
}
else
{
    if(mysql_num_rows($result) == 0)
    {
    echo'<div id="regis">
            <form id="regisForm" method="post" action="./changePassword.php?user_id=0">
                <table class = "table" id = "regisTable">
                    <tr>
                        <th colspan ="2" align="center">Change Your Password</th>
                    </tr>
                    <tr>
                        <td>Username: </td>
                        <td><input type="text" name="userName" id="userName"/></td>
                    </tr>
                        <td>Security Question: </td>
                        <td>
                        <select name="securityQuestion" id ="securityQuestion">
                        <option value="">--Select--</option>';
                            $questionList = mysql_query("SELECT * FROM nxline_questions ORDER BY question_id asc");
                            while($question=  mysql_fetch_array($questionList)){
                                    echo '<option value="' . $question['question_id']. '">' .$question['question']. '</option>';
                            }
                        echo'</select>
                        </td>
                    </tr>
                    <tr>
                        <td>Answer: </td>
                        <td><input type="text" name="answer" id ="answer"/></td>
                    </tr>
                    <tr>
                        <td>New Password: </td>
                        <td><input type="password" name="newPass" id="newPass"/></td>
                    </tr>
                    <tr>
                        <td>Retype New Password: </td>
                        <td><input type="password" name="reNewPass" id="reNewPass"/></td>
                    </tr>                    
                    <tr>
                        <td colspan ="2" align="center"><button type="submit" name="changePassButton" id="changePassButton">Change Password</button></td>
                    </tr>
                </table>
            </form>
        </div>';
                        
                if(isset($_POST['changePassButton']))
                {
                    $username =  $_POST['userName'];
                    $securityQuestion = $_POST['securityQuestion'];
                    $answer = $_POST['answer'];
                    $nPass = $_POST['newPass'];
                    $newPass = hash("sha512",$_POST['newPass']);
                    $rNPass = $_POST['reNewPass'];
                    $reNewPass = hash("sha512",$_POST['reNewPass']);
                    
                    $query = mysql_query("SELECT * FROM nxline_user WHERE user_name='" .$username."'");
                    
                if ($username == "") 
                {
                    echo'Please type in your username! <br/>';
                }
                else 
                {
                    if(mysql_num_rows($query))
                    {
                        $userPass = mysql_fetch_assoc($query);
                        
                            if($securityQuestion == "")
                            {
                                echo'Please select your Security Question! <br/>';
                            }
                            else if($answer == "")
                            {
                                echo'Please provide your correct answer! <br/>';
                            }
                            else if($nPass == "")
                            {
                                echo'Please type in your new Password! <br/>';
                            }
                            else if($rNPass == "")
                            {
                                echo'Please retype your new Password! <br/>';
                            }
                            else if(strlen($nPass) < 6)
                            {
                                echo'Your new password is too short! <br/>';
                            }                           
                            else if($nPass == $rNPass)
                            {
                                if ($userPass['user_passwd'] == $newPass)
                                {
                                    echo'You are using your current password!';
                                }
                                else if($userPass['question_id'] == $securityQuestion && $userPass['answer'] == $answer)
                                {
                                    mysql_query("UPDATE nxline_user SET user_passwd ='". $newPass."' WHERE user_name='" .$username."'");

                                    $passQuery = mysql_query("SELECT * FROM nxline_user WHERE user_passwd ='". $newPass."' AND user_name='" .$username."'");

                                    if(mysql_num_rows($passQuery))
                                    {
                                        echo'Your Password was successfully changed! <br/>';
                                    }
                                    else
                                    {
                                        echo'<br/> Please try again! <br/>';
                                    }
                                }
                                else if ($userPass['question_id'] != $securityQuestion || $userPass['answer'] != $answer)
                                {
                                    echo'Your security question and your answer do not match what you provided! <br/>';
                                }
                            }
                            else if($nPass != $rNPass)
                            {
                                echo'Your new Passwords do not match! <br/>';
                            }
                        }
                        else
                        {
                            echo'Usernam does not exist! <br/>';
                        }
                    }
                }                        
    }
    else
    {
        //display category data
        while($row = mysql_fetch_assoc($result))
        {        
            if ($user['user_id'] == $row['user_id'])
            {
                echo'<div id="changePass">
                        <form id="changePassForm" method="post" action="changePassword.php?user_id='. $user['user_id'].'">
                            <table id = "changePassTable">
                                    <tr>
                                        <th colspan ="2" align="center">Change Your Password</th>
                                    </tr>
                                    <tr>
                                        <td>Old Password: </td>
                                        <td><input type="password" name="oldPass" id="oldPass"/></td>
                                    </tr>
                                    <tr>
                                        <td>New Password: </td>
                                        <td><input type="password" name="newPass" id="newPass"/></td>
                                    </tr>
                                    <tr>
                                        <td>Retype New Password: </td>
                                        <td><input type="password" name="reNewPass" id="reNewPass"/></td>
                                    </tr>
                                    <tr>
                                        <td colspan ="2" align="center"><button type="submit" name="changePassButton" id="changePassButton">Change Password</button></td>
                                    </tr>
                            </table>
                        </form>
                    </div>';
                
                if(isset($_POST['changePassButton']))
                {
                    $oPass = $_POST['oldPass'];
                    $oldPass = hash("sha512", $_POST['oldPass']);
                    $nPass = $_POST['newPass'];
                    $newPass = hash("sha512",$_POST['newPass']);
                    $rNPass = $_POST['reNewPass'];
                    $reNewPass = hash("sha512",$_POST['reNewPass']);
                    
                    $query = mysql_query("SELECT * FROM nxline_user WHERE user_id=" .$row['user_id']);
                    
                    if(mysql_num_rows($query))
                    {
                        $userPass = mysql_fetch_assoc($query);
                        
                        if($oPass == "")
                        {
                            echo'Please type in your old Password! <br/>';
                        }
                        else if($oldPass == $userPass['user_passwd'])
                        {
                            if($nPass == "")
                            {
                                echo'Please type in your new Password! <br/>';
                            }
                            else if($rNPass == "")
                            {
                                echo'Please retype your new Password! <br/>';
                            }
                            else if(strlen($nPass) < 6)
                            {
                                echo'Your new password is too short! <br/>';
                            }                            
                            else if($nPass == $rNPass)
                            {
                                mysql_query("UPDATE nxline_user SET user_passwd ='". $newPass."' WHERE user_id=" .(int) $userPass['user_id']);
                                
                                $passQuery = mysql_query("SELECT * FROM nxline_user WHERE user_passwd ='". $newPass."' AND user_id=". (int) $userPass['user_id']);
                                
                                if(mysql_num_rows($passQuery))
                                {
                                    echo'Your Password was successfully changed! <br/>';
                                }
                                else
                                {
                                    echo'<br/> Please try again! <br/>';
                                }
                            }
                            else if($nPass != $rNPass)
                            {
                                echo'Your new Passwords do not match! <br/>';
                            }
                        }
                        else
                        {
                            echo'Your old password does not correct! <br/>';
                        }
                    }
                }
            }
        }
    }
}


require("footer.inc.php");

