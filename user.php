<?php

require("header.inc.php");

$result = mysql_query("SELECT * FROM nxline_user WHERE user_id = " . mysql_real_escape_string($_GET['user_id']));

if(!$result)
{
    echo 'The user could not be displayed, please try again later.' . mysql_error();
    
    header("Location: ./user.php?user_id=0");
}
else
{
    if(mysql_num_rows($result) == 0)
    {
        echo 'This user does not exist.';
    }
    else
    {
        //display category data
        while($row = mysql_fetch_assoc($result))
        {        
            echo'<form id="personalInfoForm" method="post" enctype="multipart/form-data" action="user.php?user_id='. $user['user_id'].'">
                    <div id="personalInfo">
                            <table id="personalInfoTable">
                                    <tr>
                                            <th colspan="2" align="center">'.$row['first_name']. ' '.$row['last_name'].'</th>
                                    </tr>
                                    <tr>
                                            <td colspan="2" align="center"><img src="' . $row['user_pic'] . '" alt="' . $row['user_name'] . '" width="200" height="200"></td>
                                    </tr>';
                            if($user['user_id'] == $row['user_id'])
                            {
                                    echo'<tr>
                                            <td colspan="2"><input type="file" name="file_up" ></td>
                                    </tr>
                                    <tr>
                                            <td colspan="2"><button type="submit" name="uploadButton" id="uploadButton">Upload</button></td>
                                    </tr>';
                            }
                                    echo'<tr>
                                            <td>Username: </td>
                                            <td>' . $row['user_name'] . '</td>                                            
                                    </tr>
                                    <tr>
                                            <td>Email: </td>
                                            <td>' . $row['email'] . '</td>                                            
                                    </tr>
                                    <tr>                                            
                                            <td>Cellphone: </td>
                                            <td>' . $row['cell_phone'] . '</td>                                             
                                    </tr>
                                    <tr>                                            
                                            <td>Homephone: </td>
                                            <td>' . $row['home_phone'] . '</td>                                                
                                    </tr>
                                    <tr>                                            
                                            <td>Role: </td>
                                            <td>' . $row['user_role_name'] . '</td>                                            
                                    </tr>';
                            if($user['user_id'] == $row['user_id'])
                            {
                                    echo'<tr>
                                            <td colspan="2" align="center"><a href ="changePassword.php?user_id=' . $user['user_id'] . '">Change Password</a></td>
                                    </tr>';
                            }
                            echo'</table>
                    </div>
                </form>';
                

                if (isset($_POST['uploadButton'])) {
                    $link = "./img/avatar/" . $_FILES["file_up"]["name"];

                    if ($_FILES["file_up"]["error"] > 0) {
                        echo "Return Code: " . $_FILES["file_up"]["error"] . ". Cannot upload!<br />";
                    } else if (file_exists("./img/avatar/" . $_FILES["file_up"]["name"])) {
                        unlink($link);

                        move_uploaded_file($_FILES["file_up"]["tmp_name"], "./img/avatar/" . $_FILES["file_up"]["name"]);                        
                        
                        mysql_query("UPDATE nxline_user SET user_pic = '" . $link . "' WHERE user_id = " . (int) $row['user_id']);
                        
                        echo'<script> window.location="user.php?user_id='. $user['user_id'].'"; </script> ';
                       
                    } else {
                        move_uploaded_file($_FILES["file_up"]["tmp_name"], "./img/avatar/" . $_FILES["file_up"]["name"]);

                        mysql_query("UPDATE nxline_user SET user_pic = '" . $link . "' WHERE user_id = " . (int) $row['user_id']);
                        
                        echo'<script> window.location="user.php?user_id='. $user['user_id'].'"; </script> ';                        
                    }
                }
            
            echo'<form id="ownerShopForm" method="post" action="user.php?user_id='. $user['user_id'].'">
                    <div id="ownerShop">
                        <table id="ownerShopTable">
                            <tr>
                                <th align="center">Restaurants/Bars</th>';
                                    if($user['user_id'] == $row['user_id'] && $user['user_role_name'] == "Owner")
                                    {
                                        echo'<th align="right"><a href ="create_shop.php?user_id='. $user['user_id'] . '"> Create New Shop </a></th>';
                                    }
                        echo'</tr>
                            <tr>
                                <td colspan="2">';
                                $query = mysql_query("SELECT * FROM nxline_shop WHERE user_id=" .$row['user_id']." ORDER BY shop_name ASC");
                                
                                if (mysql_num_rows($query)) {
                                    while ($nameResult = mysql_fetch_array($query)) {
                                        echo'<a href="shop.php?shop_id='.$nameResult['shop_id'].'">
                                                    <table id = "ownerResultTable">
                                                        <tr>
                                                            <th rowspan="3"><img src="'.$nameResult['shop_pic'].'" alt="'. $nameResult['shop_name'].'"></th>
                                                            <td><b>' . $nameResult['shop_name'] . '</b></td>';
                                                            if($user['user_id'] == $row['user_id'] && $user['user_role_name'] == "Owner")
                                                            {                                        
                                                                echo'<td width="100px"><a href = "addAddress.php?shop_id='.$nameResult['shop_id'].'"> Add Address</a></td>';
                                                            }
                                                    echo'</tr>
                                                        <tr>
                                                            <td colspan="2">' . $nameResult['star'] . '</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">' . $nameResult['shop_desc'] . '</td>
                                                        </tr>
                                                    </table>
                                            </a>
                                            <br>';                                        
                                    }                                    
                                }
                        echo'</td>
                            </tr>
                        </table>
                    </div>
            </form>';
        }
    }
}

require("footer.inc.php");
