<?php

require("header.inc.php");

if(isset($_POST['regisButton']))
{
    $username = $_POST['userName'];
    $password = $_POST['password'];
    $rePassword = $_POST['rePassword'];
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $sex = $_POST['sex'];
    $email = $_POST['email'];
    $cellPhone = $_POST['cellPhone'];
    $role = $_POST['role'];

    $checkUsername = mysql_query("SELECT `user_id` FROM `nxline_user` WHERE `user_name` = '" . mysql_real_escape_string($username). "'LIMIT 1");
    
    $checkEmail = mysql_query("SELECT `user_id` FROM `nxline_user` WHERE `email` = '" . mysql_real_escape_string($email). "'LIMIT 1");
    
    if(mysql_num_rows($checkUsername) == 1){
        echo "Oops! Username is already in use!<br />";  
    }
    else if($password != $rePassword){
       echo "Oops! The two entered passwords don't match!<br />";
    }
    else if(strlen($username) > 15){
       echo "Username is too long! Must be between 6 and 15 characters!<br />";
    }
    else if(strlen($username) < 6){
       echo "Username is too short! Must be between 6 and 15 characters!<br />";
    }
    else if(strlen($password) > 15){
       echo "Password is too long! Must be between 6 and 15 characters!<br />";
    }
    else if(strlen($password) < 6){
       echo "Password is too short! Must be between 6 and 15 characters!<br />";
    }
    else if(preg_match('/[^0-9A-Za-z]/',$username)){
       echo "Username can contain numbers and letters only<br />";
    }
    else if($lastName == "")
    {
        print("Please fill in your Last Name!<br />");
    }
    else if($firstName == "")
    {
        print("Please fill in your First Name!<br />");
    }
    else if($email == "")
    {
        print("Please fill in your Email address!<br />");
    }
    else if(mysql_num_rows($checkEmail) == 1){
    echo "Oops! Email is already in use!<br />";
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
       print("Incorrect email format!<br />");       
    }
    else if($cellPhone == "")
    {
        print("Please fill in your cell phone number!<br />");
    }
    else if($role =="")
    {
        print("Please select your role!");
    }
    else
    {
        $passwordHash = hash("sha512", $_POST['password']);

        mysql_query("INSERT INTO nxline_user (user_name, user_passwd, last_name, first_name, email, cell_phone, user_role_name) "
                . "VALUES ('" .$username."', '" . $passwordHash."','" . $lastName."','" . $firstName."','". $email."',   '" . $cellPhone."', '". $role."')");
        
        $query = mysql_query("SELECT * FROM nxline_user WHERE user_name ='". $username."'");
        $resultRegis = mysql_fetch_assoc($query);
        
            print("<h2>Congratulation, ". $resultRegis['user_name']."! You have registered successfully!</h2>");
            
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
            
                require("footer.inc.php");
                
                exit();
    }
}
//    echo'<div id="regis">
//            <form id="regisForm" method="post" action="./register.php">
//                <table class = "table" id = "regisTable">
//                    <tr>
//                        <th colspan ="2" align="center">Registration</th>
//                    </tr>
//                    <tr>
//                        <td>Username*: </td>
//                        <td><input type="text" name="userName" id="userName"/></td>
//                    </tr>
//                    <tr>
//                        <td>Password*: </td>
//                        <td><input type="password" name="password" id="password"/></td>
//                    </tr>
//                    <tr>
//                        <td>Retype Password*: </td>
//                        <td><input type="password" name="rePassword" id="rePassword"/></td>
//                    </tr>
//                        <td>Security Question*: </td>
//                        <td>
//                        <select name="securityQuestion" id ="securityQuestion">
//                        <option value="">--Select--</option>';
//                            $questionList = mysql_query("SELECT * FROM nxline_questions ORDER BY question_id asc");
//                            while($question=  mysql_fetch_array($questionList)){
//                                    echo '<option value="' . $question['question_id']. '">' .$question['question']. '</option>';
//                            }
//                        echo'</select>
//                        </td>
//                    </tr>
//                    <tr>
//                        <td>Answer*: </td>
//                        <td><input type="text" name="answer" id ="answer"/></td>
//                    </tr>
//                    <tr>
//                        <td>First Name*: </td>
//                        <td><input type="text" name="firstName" id ="firstName"/></td>
//                    </tr>
//                    <tr>
//                        <td>Last Name*: </td>
//                        <td><input type="text" name="lastName" id ="lastName"/></td>
//                    </tr>
//                    <tr>
//                        <td>Sex*: </td>
//                        <td><select name="sex" id ="sex">
//                            <option value="">--Select--</option>
//                            <option value="Male">Male</option>
//                            <option value="Female">Female</option>
//                            <option value="Other">Other</option>
//                            </select>
//                        </td>
//                    </tr>
//                    <tr>
//                        <td>Email*: </td>
//                        <td><input type="text" name="email" id="email"/></td>
//                    </tr>
//                    <tr>
//                        <td>Cell Phone*: </td>
//                        <td><input type="text" name="cellPhone" id="cellPhone"/></td>
//                    </tr>
//                    <tr>
//                        <td>Home Phone: </td>
//                        <td><input type="text" name="homePhone" id="homePhone"/></td>
//                    </tr>
//                    <tr>
//                        <td>Role*: </td>
//                        <td><select name="role" id ="role">
//                            <option value="">--Select--</option>
//                            <option value="Customer">Customer</option>
//                            <option value="Owner">Owner</option>
//                            </select>
//                        </td>
//                    </tr>
//                    <tr>
//                        <td colspan ="2" align="center"><button type="submit" name="regisButton" id="regisButton">Register</button></td>
//                    </tr>
//                </table>
//            </form>
//        </div>';
                        
    echo'<form id="msform" method="post" action="register.php">
	<!-- progressbar -->
        <label><b>Registration</b></label>
        <br/><br/>
	<ul id="progressbar">
		<li class="active">Account Setup</li>
		<li>Contact Information</li>
		<li>Personal Details</li>
	</ul>
	<!-- fieldsets -->
	<fieldset>
		<h2 class="fs-title">Create your account</h2>
		<h3 class="fs-subtitle">Account Information</h3>
		<input type="text" name="userName" id="userName" placeholder="Username"/>
		<input type="password" name="password" id="password" placeholder="Password" />
		<input type="password" name="rePassword" id="rePassword" placeholder="Confirm Password" />
		<input type="button" name="next" class="next action-button" value="Next" />
	</fieldset>
	<fieldset>
		<h2 class="fs-title">Contact Information</h2>
                <h3 class="fs-subtitle">We will contact you for validating information</h3>
                <input type="text" name="email" id="email" placeholder="Email"/>
                <input type="text" name="cellPhone" id="cellPhone" placeholder="Cell Phone Number"/>
		<input type="button" name="previous" class="previous action-button" value="Previous" />
		<input type="button" name="next" class="next action-button" value="Next" />
	</fieldset>
	<fieldset>
		<h2 class="fs-title">Personal Details</h2>
		<h3 class="fs-subtitle">We will never sell it</h3>
                <input type="text" name="firstName" id ="firstName" placeholder="First Name"/>
                <input type="text" name="lastName" id ="lastName" placeholder="Last Name"/>
                <select name="role" id ="role" class="turnintodropdown">
                            <option value="">--Role--</option>
                            <option value="Customer">Customer</option>
                            <option value="Owner">Owner</option>
                </select>
		<input type="button" name="previous" class="previous action-button" value="Previous" />
                <input type="submit" name="regisButton" id="regisButton" value="Register" class="regisButton action-button"/>
	</fieldset>
</form>';
                        
    require("footer.inc.php");

?>
