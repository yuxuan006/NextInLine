<?php
	
require("header.inc.php");
		
echo'<div id="searchShop">
        <form id="searchForm" method="post" action="./index.php">
            <table class = "table" id = "searchTable">
                <tr>
                    <th colspan ="4" align="center"">Search Restaurant/bar</th>
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
   
    
$result = mysql_query("SELECT * FROM nxline_shop_addr WHERE shop_addr_id = " . mysql_real_escape_string($_GET['shop_addr_id']));

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
            $shopQuery = mysql_query("SELECT * FROM nxline_shop WHERE shop_id=" .$row['shop_id']);
            $shop = mysql_fetch_assoc($shopQuery);
            $foodQuery = mysql_query("SELECT * FROM nxline_foods WHERE shop_addr_id=".$row['shop_addr_id']);
            $i=0;

            if(mysql_num_rows($foodQuery))
            {
                echo $shop['shop_name'].'<br/>';
                echo "Menu";
                echo'<form id="menuInfoForm" method="post" action="menu.php?shop_addr_id='. $row['shop_addr_id'].'" vertical-align="center">
                        <div id="menuInfo">
                             <table id="menuInfoTable" border="1">';
                                while($food = mysql_fetch_array($foodQuery))
                                {                                        
                                    echo'<tr>
                                            <td align="center" rowspan="2"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="'.$i++.'" /></td>
                                            <td rowspan="2" width="100px"><a href="#"><img src="'.$food['food_pic'].'" alt="'. $food['food_name'].'"></a></td>
                                            <th width="300px">'.$food['food_name'].'</th>
                                            <td width="300px" style="text-align:center">
                                                <input type="text" value="1" id="foodQuantity[]" name="foodQuantity[]">
                                            </td>
                                            <td width="300px" style="text-align:center">$'. $food['food_price'].'</td>
                                        </tr>
                                        <tr>                                            
                                            <td colspan="3" width="800px">'.$food['food_desc'].'</td>
                                        </tr>
                                        <tr>                                            
                                            <input type="hidden" value="'.$food['food_id'].'" id="foodID[]" name="foodID[]">
                                            <input type="hidden" value="'.$food['food_price'].'" id="foodPrice[]" name="foodPrice[]">
                                        </tr>';
                                }
                            if(!isset($_POST['orderFoodButton']))
                            { 
                                echo'<tr><td colspan="5" align="center"><button type="submit" name="orderFoodButton" id="orderFoodButton">Add to Your Table</button></td></tr>';
                            }
                        echo'</table>
                    </div>
                </form>
                <br/><br/><br/>';                                      
            }
            
            if(isset($_POST['orderFoodButton']))
            {
                $orderDateTime = date("Y-m-d H:i:s");
                
                mysql_query("INSERT INTO nxline_order (order_time, shop_id, user_id, shop_addr_id) "
                        . "VALUES ('".$orderDateTime."', ".$row['shop_id'].", ".$user['user_id'].", ".$row["shop_addr_id"]." )");
            	$orderIdQuery=mysql_query("SELECT MAX(order_id) AS current_order_id FROM nxline_order WHERE user_id=".$user['user_id']);
            	$orderId=mysql_fetch_assoc($orderIdQuery);
                
		echo '<form id="orderListForm" method="post" action="payment.php?order_id='. $orderId['current_order_id'].'" vertical-align="center">
                        <div id="orderList">
                            <table id="orderListTable">
                                <tr>
                                    <th width="300" align="center"><i>Item</i></th>
                                    <th width="300px"><i>Qty.</i></th>
                                    <th width="300px"><i>Price</i></th>
                                </tr>';
                                foreach($_POST['checkbox'] as $i)
                                {   
                                    mysql_query("INSERT INTO nxline_order_list (food_id, food_price, food_quantity, pricexquantity, user_id, shop_addr_id, order_id) "
                                            . "VALUES (".$_POST['foodID'][$i].", ".$_POST['foodPrice'][$i].", ".$_POST['foodQuantity'][$i].", ".$_POST['foodQuantity'][$i]*$_POST['foodPrice'][$i].", ".$user['user_id'].", ".$row['shop_addr_id'].", ".$orderId['current_order_id'].")" );
                                }
                                
                                $orderQuery=mysql_query("SELECT *FROM nxline_order_list WHERE order_id=". $orderId['current_order_id']);

                                while($order=mysql_fetch_array($orderQuery))
                                {
                                    $foodOrderedQuery = mysql_query("SELECT * FROM nxline_foods WHERE food_id=".$order['food_id']);
                                    $foodOrdered = mysql_fetch_assoc($foodOrderedQuery);
                                    
                                    echo'<tr>
                                            <td width="300px">'. $foodOrdered['food_name'].'</td>
                                            <td width="300px"> '. $order['food_quantity'].'</td>
                                            <td width="300px"> $'. $order['pricexquantity'].'</td>    
                                	</tr>';
                                }
                            echo'<tr></tr><tr>
                                    <td colspan="3" style="text-align:right">Tips: $ <input type="text" name="tips" id="tips" value="0.00"/></td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align:right">Tax: $'.  getTax($orderId['current_order_id']).'</td>
                                </tr>
                                <tr>
                                    <td colspan="3"width="900px" style="text-align:right">Total: $'.getTotalAmount($orderId['current_order_id']).'</td>
                                </tr>
                                <tr>
                                    <td colspan ="1" align="center"><button type="submit" name="orderButton" id="orderButton">Place your To Go Order</button></td>
                                    <td colspan ="2" align="center"><button type="submit" name="orderDineInButton" id="orderDineInButton">Place your Dine In Order</button></td>
                                </tr>                               
                            </table>
                        </div>
                    </form>
                    <br/><br/><br/>';  
            }            
        }
    }
}

require("footer.inc.php");

