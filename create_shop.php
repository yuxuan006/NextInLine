<?php
require("header.inc.php");

$result = mysql_query("SELECT * FROM nxline_user WHERE user_id = " . mysql_real_escape_string($_GET['user_id']));

if(!$result)
{
    echo 'The user could not be displayed, please try again later.' . mysql_error();
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
            if($user['user_role_name'] == "Owner")
            {              
                echo'<div id="createShop">
                        <form id="createShopForm" method="post" enctype="multipart/form-data" action="create_shop.php?user_id='. $user['user_id'].'">
                            <table id = "createShopTable">
                                    <tr>
                                        <th colspan ="2" align="center">Create New Shop</th>
                                    </tr>
                                    <tr>
                                        <td>Shop Name: </td>
                                        <td><input type="text" name="shopName" id="shopName"/></td>
                                    </tr>
                                    <tr>
                                        <td>Shop Description: </td>
                                        <td><textarea rows="4" cols="50" name="shopDesc" id="shopDesc" placeholder="Enter text here..."></textarea></td>
                                     </tr>
                                     <tr>	
                                        <td>Category: </td>
                                        <td>
                                        <select name="category" id ="category">
                                        <option value="">--Select--</option>';
                                                $categoryList = mysql_query("SELECT * FROM nxline_shop_category ORDER BY cat_name asc");
                                                while ($category = mysql_fetch_array($categoryList)) {
                                                        echo'<option value="' . $category['cat_id'] . '">' . $category['cat_name'] . '</option>';
                                                }
                                        echo'</select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Picture: </td>
                                        <td><input type="file" name="file_up" /></td>
                                    </tr>
                                    <tr>
                                        <td colspan ="2" align="center"><button type="submit" name="createShopButton" id="createShopButton">Create Shop</button></td>
                                    </tr>
                            </table>
                        </form>
                    </div>';
                                        
                if(isset($_POST['createShopButton']))
                {
                    $shopName = $_POST['shopName'];
                    $shopeDesc = $_POST['shopDesc'];
                    $cate = $_POST['category'];
                    $link = "./img/upload/" . $_FILES["file_up"]["name"];
                    
                    if($shopName == "")
                    {
                        echo'Please provide your shop name!<br/>';
                    }
                    else if($shopeDesc == "")
                    {
                        echo'Please describe your shop!<br/>';
                    }
                    else if($shopeDesc == "")
                    {
                        echo'Please select a category!<br/>';
                    }
                    if ($_FILES["file_up"]["error"] > 0)
                    {
                        echo "Return Code: " . $_FILES["file_up"]["error"] . " Please choose a photo to upload!<br />";
                    }                    
                    else if(file_exists("./img/upload/" . $_FILES["file_up"]["name"]))
                    {
                        unlink($link);
                        
                        move_uploaded_file($_FILES["file_up"]["tmp_name"], "./img/upload/" . $_FILES["file_up"]["name"]);                        
                        
                        mysql_query("INSERT INTO nxline_shop(user_id, shop_name, shop_desc, cat_id, shop_pic ) VALUES(". (int) $_GET['user_id'].", '". $shopName."', '". mysql_escape_string($shopeDesc)."', ". (int) $cate.", '". $link."')");
                    }
                    else
                    {
                        move_uploaded_file($_FILES["file_up"]["tmp_name"],
                        "./img/upload/" . $_FILES["file_up"]["name"]);
                        
                        mysql_query("INSERT INTO nxline_shop(user_id, shop_name, shop_desc, cat_id, shop_pic ) VALUES(". (int) $_GET['user_id'].", '". $shopName."', '". mysql_escape_string($shopeDesc)."', ". (int) $cate.", '". $link."')");
                    }
                }
            }
            else
            {
                header("Location: ./index.php");
            }
        }
    }
}

require("footer.inc.php");
?>
