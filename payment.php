<?php
	
require("header.inc.php");

print("<br/><br/>");

$result = mysql_query("SELECT * FROM nxline_order WHERE order_id = " . mysql_real_escape_string($_GET['order_id']));

if(!$result)
{
    echo 'The order could not be displayed, please try again later.' . mysql_error();
}
else
{
    if(mysql_num_rows($result) == 0)
    {
        echo 'This order does not exist.';
    }
    else
    {
        //display category data
        while($row = mysql_fetch_assoc($result))
        { 
            if(isset($_POST['orderButton']))
            {
                $tip = number_format((float)$_POST['tips'], 2, '.', '');
                
                mysql_query("UPDATE nxline_order SET tips = ".$tip.", order_type = 'To Go' WHERE order_id = ".$row['order_id']);
                
		echo'<form id="paymentForm" method="post" action="payment.php?order_id='. $row['order_id'].'" vertical-align="center">
                        <div id="payment">
                            <table id="paymentTable">
                                <tr>
                                    <td width="300" style="text-align:center" ><i>Item</i></td>
                                    <td width="300px" style="text-align:center"><i>Price</i></td>
                                    <td width="300px" style="text-align:center"><i>Qty</i></td>
                                </tr>';	
                
                $orderQuery = mysql_query("SELECT * FROM nxline_order_list WHERE order_id=" .$row['order_id']);
							
        	while($order=mysql_fetch_array($orderQuery))
                {
                    $foodOrderedQuery = mysql_query("SELECT * FROM nxline_foods WHERE food_id=".$order['food_id']);
                    $foodOrdered = mysql_fetch_assoc($foodOrderedQuery);

                    echo'<tr>
                            <td width="300px" style="text-align:center">'.$foodOrdered['food_name'].'</a></td>
                            <td width="300px" style="text-align:center">$'. $order['pricexquantity'].'</td>
                            <td width="300px" style="text-align:center">'. $order['food_quantity'].'</td>
                        </tr>';
                }
                
                            echo '<tr>
                                </tr>
                                <tr>
                                </tr>
                                <tr>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align:right">Tips: $'.$tip.'</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align:right">Tax: $'.  getTax($row['order_id']).'</td>
                                </tr>
                                <tr>
                                    <td colspan="3"width="900px" style="text-align:right">Total: $'.  (getTotalAmount($row['order_id']) + $tip).'</td>
                                </tr>                                            
                            </table>
                        </div>
                    </form>
                    </br>';
                        
                echo'<form id="shippingInfoForm" method="post" action="payment.php?order_id='. $row['order_id'].'">
                        <div id="shippingInfo">
                            <table id="shippingInfoTable">
                                <tr>
                                    <th colspan ="2" align="center">Billing Address</th>
                    		</tr>
                                <tr>
                                    <td>Full Name: </td>
                                    <td><input type="text" name="fullName" id="fullName"/></td>
                                </tr>                                
                                <tr>
                                    <td>Address:</td>
                                    <td><input type="text" name="address" id="address"/></td>
                                </tr>
                                <tr>
                                    <td>City:</td>
                                    <td><input type="text" name="city" id="city"/></td>
                                </tr>
                                <tr>
                                    <td>State:</td>
                                    <td><input type="text" name="state" id="state"/></td>
                                </tr>
                                <tr>
                                    <td>Country:</td>
                                    <td><input type="text" name="country" id="country"/></td>
                                </tr>                                 
                                <tr>
                                    <td>Postal Code:</td>
                                    <td><input type="text" name="postalCode" id="postalCode"/></td>
                                </tr>
                                <tr>
                                    <td>Phone Number:</td>
                                    <td><input type="text" name="phoneNumber" id="phoneNumber"/></td>
                                </tr>                                
                                </br></br>';

                        echo '<tr>
                                <th colspan ="2" align="center">Card Information</th>
                            </tr>
                            <tr>
                                <td>Payment method:</td>
                                <td><a href="#"><img alt="" title="" src="http://www.credit-card-logos.com/images/multiple_credit-card-logos-1/credit_card_logos_16.gif" width="336" height="50" border="0" /></a></td>
                            </tr>
                            <tr>
                                <td>Name on Card:</td>
                                <td><input type="text" name="name" id="name"/></td>
                            </tr>
                            <tr>
                                <td>Card Number:</td>
                                <td><input type="text" name="cardNumber" id="cardNumber"/></td>
                            </tr>
                            <tr>
                                <td>Expire Date:</td>
                            <td>
                                <select name="month" id ="month">
                                    <option value="">--Month--</option>
                                    <<option value="January">January</option>
                                    <option value="February">February</option>
                                    <option value="March">March</option>
                                    <option value="April">April</option>
                                    <option value="May">May</option>
                                    <option value="June">June</option>
                                    <option value="July">July</option>
                                    <option value="August">August</option>
                                    <option value="September">September</option>
                                    <option value="October">October</option>
                                    <option value="November">November</option>
                                    <option value="December">December</option>                                         
                                </select>

                                <select name="year" id ="year">
                                    <option value="">--year--</option>
                                    <option value="2014">2014</option>
                                    <option value="2015">2015</option>
                                    <option value="2016">2016</option>
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>                                         
                                </select>
                            </td>
                            </tr>
                            <tr>
                                <td>CVV:</td>
                                <td><input type="text" name="CVV" id="CVV"/></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align:center"><button type="submit" name="checkoutButton" id="checkoutButton">Checkout</button></td>
                            </tr>
                        </div>
                    </table>
                </form>
                </br>';
            }
            else if(isset($_POST['orderDineInButton']))
            {
                $tip = number_format((float)$_POST['tips'], 2, '.', '');
                
                mysql_query("UPDATE nxline_order SET tips = ".$tip.", order_type = 'Dine In' WHERE order_id = ".$row['order_id']);
                
		echo'<form id="paymentForm" method="post" action="payment.php?order_id='. $row['order_id'].'" vertical-align="center">
                        <div id="payment">
                            <table id="paymentTable">
                                <tr>
                                    <td width="300" style="text-align:center" ><i>Item</i></td>
                                    <td width="300px" style="text-align:center"><i>Price</i></td>
                                    <td width="300px" style="text-align:center"><i>Qty</i></td>
                                </tr>';	
                
                $orderQuery = mysql_query("SELECT * FROM nxline_order_list WHERE order_id=" .$row['order_id']);
							
        	while($order=mysql_fetch_array($orderQuery))
                {
                    $foodOrderedQuery = mysql_query("SELECT * FROM nxline_foods WHERE food_id=".$order['food_id']);
                    $foodOrdered = mysql_fetch_assoc($foodOrderedQuery);

                    echo'<tr>
                            <td width="300px" style="text-align:center">'.$foodOrdered['food_name'].'</a></td>
                            <td width="300px" style="text-align:center">$'. $order['pricexquantity'].'</td>
                            <td width="300px" style="text-align:center">'. $order['food_quantity'].'</td>
                        </tr>';
                }
                
                            echo '<tr>
                                </tr>
                                <tr>
                                </tr>
                                <tr>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align:right">Tips: $'.$tip.'</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align:right">Tax: $'.  getTax($row['order_id']).'</td>
                                </tr>
                                <tr>
                                    <td colspan="3"width="900px" style="text-align:right">Total: $'.  (getTotalAmount($row['order_id']) + $tip).'</td>
                                </tr>                                            
                            </table>
                        </div>
                    </form>
                    </br>';
                        
                echo'<form id="shippingInfoForm" method="post" action="payment.php?order_id='. $row['order_id'].'">
                        <div id="shippingInfo">
                            <table id="shippingInfoTable">
                                <tr>
                                    <th colspan ="2" align="center">Customer Information: </th>
                    		</tr>
                                <tr>
                                    <td>Full Name: </td>
                                    <td><input type="text" name="fullName" id="fullName"/></td>
                                </tr>                                
                                <tr>
                                    <td>Phone Number:</td>
                                    <td><input type="text" name="phoneNumber" id="phoneNumber"/></td>
                                </tr>
                                <tr>
                                    <td>Party Size:</td>
                                    <td><input type="text" name="partySize" id="partySize"/></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align:center"><button type="submit" name="checkoutDineInButton" id="checkoutDineInButton">Checkout</button></td>
                                </tr>
                            </table>
                        </div>
                    </form>    
            </br></br>';
            }
            else if (isset($_POST['orderReserveButton']))
            {
                $tip = number_format((float)$_POST['tips'], 2, '.', '');
                
                mysql_query("UPDATE nxline_order SET tips = ".$tip.", order_type = 'Reserve' WHERE order_id = ".$row['order_id']);
                
		echo'<form id="paymentForm" method="post" action="payment.php?order_id='. $row['order_id'].'" vertical-align="center">
                        <div id="payment">
                            <table id="paymentTable">
                                <tr>
                                    <td width="300" style="text-align:center" ><i>Item</i></td>
                                    <td width="300px" style="text-align:center"><i>Price</i></td>
                                    <td width="300px" style="text-align:center"><i>Qty</i></td>
                                </tr>';	
                
                $orderQuery = mysql_query("SELECT * FROM nxline_order_list WHERE order_id=" .$row['order_id']);
							
        	while($order=mysql_fetch_array($orderQuery))
                {
                    $foodOrderedQuery = mysql_query("SELECT * FROM nxline_foods WHERE food_id=".$order['food_id']);
                    $foodOrdered = mysql_fetch_assoc($foodOrderedQuery);

                    echo'<tr>
                            <td width="300px" style="text-align:center">'.$foodOrdered['food_name'].'</a></td>
                            <td width="300px" style="text-align:center">$'. $order['pricexquantity'].'</td>
                            <td width="300px" style="text-align:center">'. $order['food_quantity'].'</td>
                        </tr>';
                }
                
                            echo '<tr>
                                </tr>
                                <tr>
                                </tr>
                                <tr>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align:right">Tips: $'.$tip.'</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align:right">Tax: $'.getTax($row['order_id']).'</td>
                                </tr>
                                <tr>
                                    <td colspan="3"width="900px" style="text-align:right">Total: $'.  (getTotalAmount($row['order_id']) + $tip).'</td>
                                </tr>
                                <tr>
                                    <td colspan ="3" align="center"><button type="submit" name="submitButton" id="submitButton">Submit</button></td>
                                </tr>                                
                            </table>
                        </div>
                    </form>
                    </br>';                
            }
            
            if(isset($_POST['checkoutButton']))
            { 
		$name = $_POST['name'];
                $address = $_POST['address'];
                $city = $_POST['city'];
                $state = $_POST['state'];
                $country = $_POST['country'];
                $postalcode = $_POST['postalCode'];
                $cardnumber = $_POST['cardNumber'];
                $month = $_POST['month'];
                $year = $_POST['year'];
                $CVV = $_POST['CVV'];
                $fullName = $_POST['fullName'];
                $phoneNumber = $_POST['phoneNumber'];

                if($month == "" || $year == "")
                {
                    echo'Please select the expire date! </br>';
                }
                else if ($cardnumber == "")
                {
                    echo'Please type the cardNumber! </br>';
                }
                else if ($CVV == "")
                {
                    echo'Please type the CVV! </br>';
                }
                else
                {   
                    mysql_query("INSERT INTO nxline_user_address (address, city, state, country, zip_code, user_id) "
                        . "VALUES ('".$address."', '".$city."', '".$state."', '".$country."', '".$postalcode."', ".$user['user_id']." )");
                        
                    mysql_query("INSERT INTO nxline_payment (card_number, sec_code, user_id, shop_id, order_id, card_full_name, expire_date, shop_addr_id) "
                        . "VALUES ('".$cardnumber."', '".$CVV."', ".$user['user_id'].", ".$row['shop_id'].", ".$row['order_id'].", '".$name."', '".$month." ".$year."', ".$row['shop_addr_id']." )");
                        
                    $paymentQuery = mysql_query("SELECT MAX(pay_id) AS current_pay_id FROM nxline_payment WHERE user_id =".$user['user_id']);
                    $payment = mysql_fetch_assoc($paymentQuery);
                        
                    mysql_query("UPDATE nxline_order SET tax = ".getTax($row['order_id']).", total_amount = ".(getTotalAmount($row['order_id']) + $row['tips']).", pay_id=".$payment['current_pay_id'].", fullName = '".$fullName."', phoneNumber = '".$phoneNumber."' WHERE order_id = ".$row['order_id']);
                        
                    mysql_query("INSERT INTO nxline_notification (user_id, order_id, sendDateTime) VALUES (".$user['user_id'].", ".$row['order_id'].", '".$row['order_time']."')");
                        
                    $shopQuery = mysql_query("SELECT * FROM nxline_shop WHERE shop_id =".$row['shop_id']);
                    $shop = mysql_fetch_assoc($shopQuery);
                        
                    mysql_query("INSERT INTO nxline_notification (user_id, order_id, sendDateTime) VALUES (".$shop['user_id'].", ".$row['order_id'].", '".$row['order_time']."')");
                        
                    $message = "Thank you for ordering food at ".$shop['shop_name']."! Please view your order information in your mailbox and we will notify a wait time for you soon!";
                    echo "<script type='text/javascript'>alert('$message');</script>";
                    echo'<script> window.location="./index.php"; </script> ';
                }
            }
            else if (isset ($_POST['checkoutDineInButton']))
            {
                $fullName = $_POST['fullName'];
                $phoneNumber = $_POST['phoneNumber'];
                $partySize = $_POST['partySize'];

                if($fullName == "")
                {
                    echo'Please enter your fullName! </br>';
                }
                else if ($phoneNumber == "")
                {
                    echo'Please enter your Phone Number! </br>';
                }
                else if ($partySize == "")
                {
                    echo'Please let us know how many people are going! </br>';
                }
                else
                {       
                    $paymentQuery = mysql_query("SELECT MAX(pay_id) AS current_pay_id FROM nxline_payment WHERE user_id =".$user['user_id']);
                    $payment = mysql_fetch_assoc($paymentQuery);
                        
                    mysql_query("UPDATE nxline_order SET tax = ".getTax($row['order_id']).", total_amount = ".(getTotalAmount($row['order_id']) + $row['tips']).","
                            . " fullName = '".$fullName."', phoneNumber = '".$phoneNumber."', party_size = ".(int)$partySize." WHERE order_id = ".$row['order_id']);
                        
                    mysql_query("INSERT INTO nxline_notification (user_id, order_id, sendDateTime) VALUES (".$user['user_id'].", ".$row['order_id'].", '".$row['order_time']."')");
                        
                    $shopQuery = mysql_query("SELECT * FROM nxline_shop WHERE shop_id =".$row['shop_id']);
                    $shop = mysql_fetch_assoc($shopQuery);
                        
                    mysql_query("INSERT INTO nxline_notification (user_id, order_id, sendDateTime) VALUES (".$shop['user_id'].", ".$row['order_id'].", '".$row['order_time']."')");
                        
                    $message = "Thank you for ordering food at ".$shop['shop_name']."! Please view your order information in your mailbox and we will notify a wait time for you soon!";
                    echo "<script type='text/javascript'>alert('$message');</script>";
                    echo'<script> window.location="./index.php"; </script> ';
                }                
            }
            else if (isset ($_POST['submitButton']))
            {
                $sendDateTime = date("Y-m-d H:i:s");
                
                $shopQuery = mysql_query("SELECT * FROM nxline_shop WHERE shop_id =".$row['shop_id']);
                $shop = mysql_fetch_assoc($shopQuery);                
                
                $notiQuery = mysql_query("SELECT * FROM nxline_notification WHERE order_id=".$row['order_id']." AND user_id =".$shop['user_id']);
                $noti = mysql_fetch_assoc($notiQuery);
                
                $reservationQuery = mysql_query("SELECT * FROM nxline_reservation WHERE reservation_id=".$noti['reservation_id']);
                $reservation = mysql_fetch_assoc($reservationQuery);
                
                mysql_query("UPDATE nxline_order SET tax = ".getTax($row['order_id']).", total_amount = ".(getTotalAmount($row['order_id']) + $row['tips']).","
                            . " fullName = '".$reservation['fullName']."', phoneNumber = '".$reservation['phoneNumber']."', party_size = ".$reservation['party_size']." WHERE order_id = ".$row['order_id']);
                
                mysql_query("UPDATE nxline_notification SET isRead = 0, sendDateTime = '".$sendDateTime."' WHERE noti_id =".$noti['noti_id']);
                
                mysql_query("UPDATE nxline_reservation SET isOrdered = 1 WHERE reservation_id =".$reservation['reservation_id']);
                
                    $message = "Thank you for ordering food at ".$shop['shop_name']."! Please view your order information in your mailbox!";
                    echo "<script type='text/javascript'>alert('$message');</script>";
                    echo'<script> window.location="./index.php"; </script> ';                
                
                
            }
	}
    }
}
	
require("footer.inc.php");
