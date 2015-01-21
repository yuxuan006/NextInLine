<?php
require("header.inc.php");

echo'<div id="searchShop">
        <form id="searchForm" method="post" action="./index.php">
            <table class = "table" id = "searchTable">
                <tr>
                    <th colspan ="7" align="center"">Order Ahead or Place To Go Order</th>
                </tr>
                <tr>
                    <td>Name: </td>
                    <td><input type="text" name="shopName" id="shopName" placeholder="Restaurant/Bar&#39;s name"/><form id="logoutForm" method="post" action="./index.php"></td>
                    <td>Zip Code: </td>
                    <td><input type="text" name="zip" id="zip" placeholder="E.g: 44601"/></td>
                    <td>Category: </td>
                    <td>
                    <select name="category" id ="category">
                    <option value="">--Select--</option>';
                        $categoryList = mysql_query("SELECT * FROM nxline_shop_category ORDER BY cat_name asc");
                        while ($category = mysql_fetch_array($categoryList)) {
                            echo'<option value="' . $category['cat_name'] . '">' . $category['cat_name'] . '</option>';
                        }
                    echo'</select>
                    </td>
                    <td><button type="submit" name="searchButton" id="searchButton">Search</button></td>
                </tr>
            </table>
        </form>
    </div>';

if (isset($_POST['searchButton'])) {
    $name = $_POST['shopName'];
    $zip = $_POST['zip'];
    $cate = $_POST['category'];

    $queryZip = mysql_query("SELECT * FROM nxline_shop_addr WHERE zip_code = '" . mysql_real_escape_string($zip) . "'");

    $queryCat = mysql_query("SELECT * FROM nxline_shop_category WHERE cat_name = '" . mysql_real_escape_string($cate) . "'");

    $cateResult = mysql_fetch_array($queryCat);

    if ($name != "" && $zip != "" && $cate != "") {
        while ($zipResult = mysql_fetch_array($queryZip)) {
            $queryName = mysql_query("SELECT * FROM nxline_shop WHERE shop_name LIKE '%" . mysql_real_escape_string($name) . "%' AND shop_id IN (" . (int) mysql_real_escape_string($zipResult['shop_id']) . ") AND cat_id = " . (int) $cateResult['cat_id'] . " ORDER BY shop_name asc");

            if (mysql_num_rows($queryName)) {
                while ($nameResult = mysql_fetch_array($queryName)) {
                    echo '<a href="shop.php?shop_id=' . $nameResult['shop_id'] . '">' . $nameResult['shop_name'] . '</a><br/>';
                }
            }
        }
    } else if ($name != "") {
        if ($zip != "") {
            while ($zipResult = mysql_fetch_array($queryZip)) {
                $queryName = mysql_query("SELECT * FROM nxline_shop WHERE shop_name LIKE '%" . mysql_real_escape_string($name) . "%' AND shop_id IN (" . (int) mysql_real_escape_string($zipResult['shop_id']) . ") ORDER BY shop_name asc");

                if (mysql_num_rows($queryName)) {
                    $nameResult = mysql_fetch_array($queryName);

                    echo '<a href="shop.php?shop_id=' . $nameResult['shop_id'] . '">' . $nameResult['shop_name'] . '</a><br/>';
                }
            }
        } else {
            $queryName = mysql_query("SELECT * FROM nxline_shop WHERE shop_name LIKE '%" . mysql_real_escape_string($name) . "%'");

            if (mysql_num_rows($queryName)) {
                $nameResult = mysql_fetch_array($queryName);

                echo '<a href="shop.php?shop_id=' . $nameResult['shop_id'] . '">' . $nameResult['shop_name'] . '</a><br/>';
            } else {
                print("No resutl for " . $name);
            }
        }
    } else if ($zip != "") {
        if ($cate != "") {
            while ($zipResult = mysql_fetch_array($queryZip)) {
                $queryName = mysql_query("SELECT * FROM nxline_shop WHERE shop_id IN (" . (int) mysql_real_escape_string($zipResult['shop_id']) . ") AND cat_id = " . (int) $cateResult['cat_id'] . " ORDER BY shop_name asc");

                if (mysql_num_rows($queryName)) {
                    while ($nameResult = mysql_fetch_array($queryName)) {
                        echo '<a href="shop.php?shop_id=' . $nameResult['shop_id'] . '">' . $nameResult['shop_name'] . '</a><br/>';
                    }
                }
            }
        } else {
            while ($zipResult = mysql_fetch_array($queryZip)) {
                $queryName = mysql_query("SELECT * FROM nxline_shop WHERE shop_id IN (" . (int) mysql_real_escape_string($zipResult['shop_id']) . ") ORDER BY shop_name asc");

                if (mysql_num_rows($queryName)) {
                    $nameResult = mysql_fetch_array($queryName);

                    echo '<a href="shop.php?shop_id=' . $nameResult['shop_id'] . '">' . $nameResult['shop_name'] . '</a><br/>';
                }
            }
        }
    } else if ($cate != "") {
        $queryName = mysql_query("SELECT * FROM nxline_shop WHERE cat_id = " . (int) $cateResult['cat_id'] . " ORDER BY shop_name asc");

        if (mysql_num_rows($queryName)) {
            while ($nameResult = mysql_fetch_array($queryName)) {
                echo '<a href="shop.php?shop_id=' . $nameResult['shop_id'] . '">' . $nameResult['shop_name'] . '</a><br/>';
            }
        } else {
            print("No resutl for " . $cate);
        }
    }
}

print("<br/><br/>");


$result = mysql_query("SELECT * FROM nxline_shop WHERE shop_id = " . mysql_real_escape_string($_GET['shop_id']));

if(!$result)
{
    echo 'The shop could not be displayed, please try again later.' . mysql_error();
}
else
{
    if(mysql_num_rows($result) == 0)
    {
        echo 'This shop does not exist.';
    }
    else
    {
        //display category data
        while($row = mysql_fetch_assoc($result))
        {   
            
        $query = mysql_query("SELECT * FROM nxline_shop_addr WHERE shop_id=" .$row['shop_id']);

        if(mysql_num_rows($query))
        {
                while($address = mysql_fetch_array($query))
                {
                    $ownerQuery = mysql_query("SELECT * FROM nxline_user WHERE user_id=".$row['user_id']);
                    $owner = mysql_fetch_assoc($ownerQuery);
                    
                    echo'<form id="shopInfoForm" method="post" action="shop.php?shop_id='. $row['shop_id'].'">
                            <div id="shopInfo">
                                    <table id="shopInfoTable">
                                            <tr>
                                                    <th  colspan="3">'.$row['shop_name'].'</th>
                                            </tr>
                                            <tr>
                                                    <td width="100px">'.getAddrRating($address['shop_addr_id']).'</td>
                                                    <td width="300px"><a href="review.php?shop_addr_id='. $address['shop_addr_id'].'">'.getAddrTotalReview($address['shop_addr_id']).' reviews</a></td>';
                                                    if($user['user_role_name'] == 'Owner')
                                                    {
                                                        //cannot make a review!
                                                    }
                                                    else
                                                    {
                                                        if(isLoggedIn())
                                                        {
                                                            echo'<td align="right"><a href="review.php?shop_addr_id='. $address['shop_addr_id'].'">Make a review</a></td>
                                                            <td align="right"><a href="reserve.php?shop_addr_id='.$address['shop_addr_id'].'">Reserve a table</a></td>
                                                            <td align="right"><a href="menu.php?shop_addr_id='.$address['shop_addr_id'].'">Order Ahead</a></td>';
                                                        }
                                                        else
                                                        {
                                                            echo'<td align="right"><a href="login.php">Make a review</a></td>
                                                            <td align="right"><a href="reserve.php?shop_addr_id='.$address['shop_addr_id'].'">Reserve a table</a></td>
                                                            <td align="right"><a href="login.php">Order Ahead</a></td>';
                                                        }
                                                    }
                                       echo'</tr>
                                            <tr>
                                                    <td rowspan="3" width="100px"><img src="'.$row['shop_pic'].'" alt="'. $row['shop_name'].'"></td> 
                                            </tr>
                                            <tr>
                                                    <td width="300px">'. $address['address'].' <br/>'.$address['city'].' '.$address['state'].' '.$address['zip_code'].' <br/>'.$address['country'].'</td>
                                            </tr>
                                            <tr>
                                                    <td width="300px">'.$address['phone'].'</td>
                                                    <td><a href="user.php?user_id='.$owner['user_id'].'">Owner: '.$owner['first_name'].' '.$owner['last_name'].'</a></td>
                                            </tr>
                                    </table>
                            </div>
                    </form>
                    <br/>';                    
                }
        }            
        }
    }
}




require("footer.inc.php");
?>