<?php

require 'header.inc.php';

$result = mysql_query("SELECT * FROM nxline_notification WHERE noti_id = " . mysql_real_escape_string($_GET['noti_id']));

if(!$result)
{
    echo 'The notification could not be displayed, please try again later.' . mysql_error();
}
else
{
    if(mysql_num_rows($result) == 0)
    {
        echo 'This notification does not exist.';
    }
    else
    {
        //display category data
        while($row = mysql_fetch_assoc($result))
        {
            $userNotiQuery = mysql_query("SELECT * FROM nxline_user WHERE user_id=".$row['user_id']);
            $userNoti = mysql_fetch_assoc($userNotiQuery);
            
            if($row['reservation_id'] != 0)
            {
                $orderQuery = mysql_query("SELECT * FROM nxline_order WHERE order_id =".$row['order_id']);
                $order = mysql_fetch_assoc($orderQuery);
                
                $reservationQuery = mysql_query("SELECT * FROM nxline_reservation WHERE reservation_id =".$row['reservation_id']);
                $reservation = mysql_fetch_assoc($reservationQuery);

                $shopQuery = mysql_query("SELECT * FROM nxline_shop WHERE shop_id =".$reservation['shop_id']);
                $shop = mysql_fetch_assoc($shopQuery);

                $shopAddrQuery = mysql_query("SELECT * FROM nxline_shop_addr WHERE shop_addr_id =".$reservation['shop_addr_id']);
                $shopAddr = mysql_fetch_assoc($shopAddrQuery);

                $userReserveQuery = mysql_query("SELECT * FROM nxline_user WHERE user_id=".$reservation['user_id']);
                $userReserve = mysql_fetch_assoc($userReserveQuery);
                
                $foodQuery = mysql_query("SELECT * FROM nxline_foods WHERE shop_addr_id=".$shopAddr['shop_addr_id']);
                $i=0;
                
                if($row['isRead'] == 0)
                {
                    mysql_query("UPDATE nxline_notification SET isRead = 1 WHERE noti_id =" .$row['noti_id']);

                    echo'<script> window.location="message.php?noti_id='.$row['noti_id'].'"; </script> ';
                }

                if($userNoti['user_role_name'] == "Owner")
                {
                    echo'<form id="reserveMessageForm" method="post" action="message.php?noti_id='.$row['noti_id'].'">
                            <div id = "reserveMessage">
                                <table id = "reserveMessageTable" width = "1000px" border="1">
                                    <tr>
                                        <th colspan="2" align="left">You have a table reservation from '.$userReserve['first_name'].' '.$userReserve['last_name'].'</th>
                                    </tr>
                                    <tr>
                                        <td align="left">
                                            <img src="' . $userReserve['user_pic'] . '" alt="' . $userReserve['user_name'] . '" width="20" height="20"> '
                                            . '<a href = "user.php?user_id='.$userReserve['user_id'].'"><b>'.$userReserve['first_name'].' '.$userReserve['last_name'].' </b></a></br>
                                                to me.
                                        </td>
                                        <td align="right">
                                            '.$row['sendDateTime'].'
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align = "left" style="padding:15px">
                                            <p>Hello, </br>
                                            '.$userReserve['first_name'].' '.$userReserve['last_name'].' has reserved a table for '.$reservation['party_size'].' people.</p></br>
                                            <div id ="reservationInfo">
                                                <table id="reservationInfoTable">
                                                    <tr>
                                                        <th colspan="2" align="center">Shop Information</th>
                                                        <th colspan="2" align="center">Reservation Information</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Name: </th>
                                                        <td>'.$shop['shop_name'].'</td>
                                                        <th>Customer Name: </th>
                                                        <td>'.$reservation['fullName'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Address: </th>
                                                        <td>'.$shopAddr['address'].'</td>
                                                        <th>Date: </th>
                                                        <td>'.$reservation['reserve_date'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <th>City: </th>
                                                        <td>'.$shopAddr['city'].'</td>
                                                        <th>Time: </th>
                                                        <td>'.$reservation['reserve_time'].'</td>
                                                    </tr>  
                                                    <tr>
                                                        <th>State: </th>
                                                        <td>'.$shopAddr['state'].' - '.$shopAddr['zip_code'].' </td>
                                                        <th>Party Size: </th>
                                                        <td>'.$reservation['party_size'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Phone: </th>
                                                        <td>'.$shopAddr['phone'].'</td>
                                                        <th>Customer Number: </th>
                                                        <td>'.$reservation['phoneNumber'].'</td>
                                                    </tr>
                                                </table>
                                                </br>';                                                
                    
                                        if($reservation['isOrdered'] == 1)
                                        {
                                            echo'<form id="paymentForm" method="post" action="message.php?noti_id='.$row['noti_id'].'" vertical-align="center">
                                                <div id="payment">
                                                    <table id="paymentTable" width="600px">';
                                                    if($order['tableNumber'] != null)
                                                    {
                                                        echo'<tr>
                                                            <td colspan="3" style="text-align:center" ><b>Table: '.$order['tableNumber'].'</b></td>
                                                        </tr>';	
                                                    }
                                                        echo'<tr>
                                                            <td style="text-align:center" ><i>Item</i></td>
                                                            <td style="text-align:center"><i>Qty.</i></td>
                                                            <td style="text-align:center"><i>Price</i></td>
                                                        </tr>';	

                                                        $orderListQuery = mysql_query("SELECT * FROM nxline_order_list WHERE order_id=" .$row['order_id']);

                                                        while($orderList=mysql_fetch_array($orderListQuery))
                                                        {
                                                            $foodOrderedQuery = mysql_query("SELECT * FROM nxline_foods WHERE food_id=".$orderList['food_id']);
                                                            $foodOrdered = mysql_fetch_assoc($foodOrderedQuery);

                                                            echo'<tr>
                                                                    <td style="text-align:center">'.$foodOrdered['food_name'].'</a></td>
                                                                    <td style="text-align:center">'. $orderList['food_quantity'].'</td>
                                                                    <td style="text-align:center">$'. $orderList['pricexquantity'].'</td>
                                                                </tr>';
                                                        }
                                                  echo '<tr></tr><tr></tr>
                                                        <tr>
                                                            <td colspan="2" style="text-align:right">Tips: </td>
                                                            <td style="text-align:center">$'.$order['tips'].'</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" style="text-align:right">Tax: </td>
                                                            <td style="text-align:center">$'.$order['tax'].'</td>    
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" style="text-align:right">Total: </td>
                                                            <td style="text-align:center">$'.$order['total_amount'].'</td>    
                                                        </tr>                                            
                                                    </table>
                                                </div>
                                                </form>
                                                
                                                <form id="assignTableForm" method="post" action="message.php?noti_id='.$row['noti_id'].'">
                                                    <table id="assignTable" style="margin:0 auto;">';
                                                        if($order['tableNumber'] == null)
                                                        {
                                                            echo'<tr>
                                                                    <td><input type = "text" id = "tableNumber" name = "tableNumber"</td>
                                                                    <td><button type="submit" name="assignTableButton" id="assignTableButton">Assign Table</button></td>
                                                                </tr>';
                                                        }
                                                        else
                                                        {
                                                            echo'<tr>
                                                                    <td>';
                                                                    ?>
                                                                    <input type="button" value="Print" onclick="printPage('payment');">
                                                                    <?php
                                                                echo'</td>
                                                                </tr>';
                                                        }
                                                    echo'</table>
                                                </form>';
                                                    
                                            echo'</form>';
                                            
                                            if(isset($_POST['assignTableButton']))
                                            {
                                                $tableNumber = $_POST['tableNumber'];
                                                
                                                mysql_query("UPDATE nxline_order SET tablenumber =".$tableNumber." WHERE order_id=".$row['order_id']);
                                                
                                                echo'<script> window.location="message.php?noti_id='.$row['noti_id'].'"; </script> ';
                                            }
                                        }
                                        else
                                        {
                                            echo'This customer has not ordered food yet!';
                                        }                                                

                                            echo'</div>
                                        </td>                                
                                    </tr>
                                </table>
                            </div>
                        </form>';                
                }
                else if ($userNoti['user_role_name'] == "Customer")
                {
                        echo'<form id="reserveMessageForm" method="post" action="message.php?noti_id='.$row['noti_id'].'">
                                <div id = "reserveMessage">
                                    <table id = "reserveMessageTable" width = "1000px" border="1">
                                        <tr>
                                            <th colspan="2" align="left">Your reservation at '.$shop['shop_name'].'!</th>
                                        </tr>
                                        <tr>
                                            <td align="left">
                                                <img src="' . $shop['shop_pic'] . '" alt="' . $shop['shop_name'] . '" width="20" height="20"> '
                                                . '<a href = "shop.php?shop_id='.$shop['shop_id'].'"><b>'.$shop['shop_name'].'</b></a></br>
                                                    to me.
                                            </td>
                                            <td align="right">
                                                '.$row['sendDateTime'].' 
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align = "left" style="padding:15px;"></br>
                                                <p>Hello '.$userReserve['first_name'].' '.$userReserve['last_name'].', </br>
                                                 You has reserved a table at '.$shop['shop_name'].'! Please check the information below!</br></br> </p>
                                                <div id ="reservationInfo">
                                                    <table id="reservationInfoTable">
                                                        <tr>
                                                            <th colspan="2" align="center">Shop Information</th>
                                                            <th colspan="2" align="center">Reservation Information</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Name: </th>
                                                            <td>'.$shop['shop_name'].'</td>
                                                            <th>Customer Name: </th>
                                                            <td>'.$reservation['fullName'].'</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Address: </th>
                                                            <td>'.$shopAddr['address'].'</td>
                                                            <th>Date: </th>
                                                            <td>'.$reservation['reserve_date'].'</td>
                                                        </tr>
                                                        <tr>
                                                            <th>City: </th>
                                                            <td>'.$shopAddr['city'].'</td>
                                                            <th>Time: </th>
                                                            <td>'.$reservation['reserve_time'].'</td>
                                                        </tr>  
                                                        <tr>
                                                            <th>State: </th>
                                                            <td>'.$shopAddr['state'].' - '.$shopAddr['zip_code'].' </td>
                                                            <th>Party Size: </th>
                                                            <td>'.$reservation['party_size'].'</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Phone: </th>
                                                            <td>'.$shopAddr['phone'].'</td>
                                                            <th>Customer Number: </th>
                                                            <td>'.$reservation['phoneNumber'].'</td>
                                                        </tr>';
                        
                                        if($reservation['isOrdered'] == 1)
                                        {
                                            echo'<form id="paymentForm" method="post" action="message.php?noti_id='.$row['noti_id'].'" vertical-align="center">
                                                <div id="payment">
                                                    <table id="paymentTable" width="600px">';
                                                    if($order['tableNumber'] != null)
                                                    {
                                                        echo'<tr>
                                                            <td colspan="3" style="text-align:center" ><b>Table: '.$order['tableNumber'].'</b></td>
                                                        </tr>';	
                                                    }
                                                        echo'<tr>
                                                            <td style="text-align:center" ><i>Item</i></td>
                                                            <td style="text-align:center"><i>Qty.</i></td>
                                                            <td style="text-align:center"><i>Price</i></td>
                                                        </tr>';	

                                                        $orderListQuery = mysql_query("SELECT * FROM nxline_order_list WHERE order_id=" .$row['order_id']);

                                                        while($orderList=mysql_fetch_array($orderListQuery))
                                                        {
                                                            $foodOrderedQuery = mysql_query("SELECT * FROM nxline_foods WHERE food_id=".$orderList['food_id']);
                                                            $foodOrdered = mysql_fetch_assoc($foodOrderedQuery);

                                                            echo'<tr>
                                                                    <td style="text-align:center">'.$foodOrdered['food_name'].'</a></td>
                                                                    <td style="text-align:center">'. $orderList['food_quantity'].'</td>
                                                                    <td style="text-align:center">$'. $orderList['pricexquantity'].'</td>
                                                                </tr>';
                                                        }
                                                  echo '<tr></tr><tr></tr>
                                                        <tr>
                                                            <td colspan="2" style="text-align:right">Tips: </td>
                                                            <td style="text-align:center">$'.$order['tips'].'</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" style="text-align:right">Tax: </td>
                                                            <td style="text-align:center">$'.$order['tax'].'</td>    
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" style="text-align:right">Total: </td>
                                                            <td style="text-align:center">$'.$order['total_amount'].'</td>    
                                                        </tr>                                            
                                                    </table>
                                                </div>
                                                </form>';
                                        }                        
                        
                                                    echo'</table>
                                                    </br></br>
                                                </div>
                                            </td>                                
                                        </tr>';
                                    echo'</table>
                                </div>
                            </form>';
                                                    
                    if($reservation['isOrdered'] == 0)
                    {                              
                        echo $shop['shop_name'].'<br/>';
                        echo "Menu";
                        echo'<form id="menuInfoForm" method="post" action="message.php?noti_id='. $row['noti_id'].'" vertical-align="center">
                                <div id="menuInfo">
                                     <table id="menuInfoTable" border="1">';
                                        while($food = mysql_fetch_assoc($foodQuery))
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
                            </form>';
                    }
                    
                    if(isset($_POST['orderFoodButton']))
                    {
                        $orderDateTime = date("Y-m-d H:i:s");

                        mysql_query("INSERT INTO nxline_order (order_time, shop_id, user_id, shop_addr_id) "
                                . "VALUES ('".$orderDateTime."', ".$shop['shop_id'].", ".$user['user_id'].", ".$shopAddr["shop_addr_id"]." )");
                        $orderIdQuery=mysql_query("SELECT MAX(order_id) AS current_order_id FROM nxline_order WHERE user_id=".$user['user_id']);
                        $orderId=mysql_fetch_assoc($orderIdQuery);
                        
                        mysql_query("UPDATE nxline_notification SET order_id =".$orderId['current_order_id']." WHERE reservation_id = ".$reservation['reservation_id']);

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
                                                    . "VALUES (".$_POST['foodID'][$i].", ".$_POST['foodPrice'][$i].", ".$_POST['foodQuantity'][$i].", ".$_POST['foodQuantity'][$i]*$_POST['foodPrice'][$i].", ".$user['user_id'].", ".$shopAddr['shop_addr_id'].", ".$orderId['current_order_id'].")" );
                                        }

                                        $orderQuery=mysql_query("SELECT * FROM nxline_order_list WHERE order_id=". $orderId['current_order_id']);

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
                                            <td colspan="3"width="1000px" style="text-align:right">Total: $'.getTotalAmount($orderId['current_order_id']).'</td>
                                        </tr>
                                        <tr>
                                            <td colspan ="3" align="center"><button type="submit" name="orderReserveButton" id="orderReserveButton">Order Food Now</button></td>
                                        </tr>                               
                                    </table>
                                </div>
                            </form>
                            <br/><br/><br/>';  
                    }                    
                    
                }
            }
            else if($row['reservation_id'] == 0)
            {
                $orderQuery = mysql_query("SELECT * FROM nxline_order WHERE order_id =".$row['order_id']);
                $order = mysql_fetch_assoc($orderQuery);

                $shopQuery = mysql_query("SELECT * FROM nxline_shop WHERE shop_id =".$order['shop_id']);
                $shop = mysql_fetch_assoc($shopQuery);

                $shopAddrQuery = mysql_query("SELECT * FROM nxline_shop_addr WHERE shop_addr_id =".$order['shop_addr_id']);
                $shopAddr = mysql_fetch_assoc($shopAddrQuery);

                $userOrderQuery = mysql_query("SELECT * FROM nxline_user WHERE user_id=".$order['user_id']);
                $userOrder = mysql_fetch_assoc($userOrderQuery);
                
                if($row['isRead'] == 0)
                {
                    mysql_query("UPDATE nxline_notification SET isRead = 1 WHERE noti_id =" .$row['noti_id']);

                    echo'<script> window.location="message.php?noti_id='.$row['noti_id'].'"; </script> ';
                }

                if($userNoti['user_role_name'] == "Owner")
                {
                    echo'<form id="reserveMessageForm" method="post" action="message.php?noti_id='.$row['noti_id'].'">
                            <div id = "reserveMessage">
                                <table id = "reserveMessageTable" width = "1000px" border="1">
                                    <tr>
                                        <th colspan="2" align="left">You have a '.$order['order_type'].' food order from '.$userOrder['first_name'].' '.$userOrder['last_name'].'</th>
                                    </tr>
                                    <tr>
                                        <td align="left">
                                            <img src="' . $userOrder['user_pic'] . '" alt="' . $userOrder['user_name'] . '" width="20" height="20"> '
                                            . '<a href = "user.php?user_id='.$userOrder['user_id'].'"><b>'.$userOrder['first_name'].' '.$userOrder['last_name'].' </b></a></br>
                                                to me.
                                        </td>
                                        <td align="right">
                                            '.$row['sendDateTime'].'
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align = "left" style="padding:15px">
                                            <p>Hello, </br>
                                            '.$userOrder['first_name'].' '.$userOrder['last_name'].' has a '.$order['order_type'].' food order:</p></br>                                                                                           
                                                <div id ="reservationInfo">
                                                    <table id="reservationInfoTable">
                                                        <tr>
                                                            <th colspan="2" align="center">Shop Information</th>
                                                            <th colspan="2" align="center">Order Information</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Name: </th>
                                                            <td>'.$shop['shop_name'].'</td>
                                                            <th>Customer Name: </th>
                                                            <td>'.$order['fullName'].'</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Address: </th>
                                                            <td>'.$shopAddr['address'].'</td>
                                                            <th>Customer Number: </th>
                                                            <td>'.$order['phoneNumber'].'</td>                                                                
                                                        </tr>
                                                        <tr>
                                                            <th>City: </th>
                                                            <td>'.$shopAddr['city'].'</td>
                                                            <th>Order Type: </th>
                                                            <td>'.$order['order_type'].'</td>
                                                        </tr>  
                                                        <tr>
                                                            <th>State: </th>
                                                            <td>'.$shopAddr['state'].' - '.$shopAddr['zip_code'].' </td>
                                                            <th>Party Size: </th>
                                                            <td>'.$order['party_size'].'</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Phone: </th>
                                                            <td>'.$shopAddr['phone'].'</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                        
                                            <form id="reserveForm" method="post" action="message.php?noti_id='.$row['noti_id'].'">
                                                <div id="reserve">
                                                    <table id="reserveTable">';
                                                  if($order['waitTime'] == null)
                                                  {
                                                        echo'<tr>
                                                            <th align="center">Wait Time: &nbsp;&nbsp;</th>
                                                            <td>
                                                            <ul>
                                                                <li><label><input id="waitTime" name="waitTime" type="radio" value="00:30"> 00:30</label>   
                                                                <li><label><input id="waitTime" name="waitTime" type="radio" value="01:00"> 01:00</label>  
                                                                <li><label><input id="waitTime" name="waitTime" type="radio" value="01:30"> 01:30 </label>  
                                                                <li><label><input id="waitTime" name="waitTime" type="radio" value="02:00"> 02:00</label>  
                                                            </ul>
                                                            </td></br></br>
                                                        </tr>                                       
                                                        <tr>
                                                            <td align="center" colspan="2"><button type="submit" name="sendWaitTimeButton" id="sendWaitTimeButton">Submit Wait Time</button></td>
                                                        </tr>';
                                                  }
                                                  else
                                                  {
                                                        echo'<tr>
                                                            <th align="center">Wait Time: &nbsp;&nbsp;</th>
                                                            <td>
                                                            <ul>
                                                                <li><label><input id="waitTime" name="waitTime" type="radio" value="'.$order['waitTime'].'"> '.$order['waitTime'].' </label> 
                                                            </ul>
                                                            </td></br></br>
                                                            </tr>';                                                      
                                                  }
                                                    echo'</table>                            
                                                    <br/><br/>
                                                </div>
                                            </form>';
                                            
                                            echo'<form id="paymentForm" method="post" action="message.php?noti_id='.$row['noti_id'].'" vertical-align="center">
                                                <div id="payment">
                                                    <table id="paymentTable" width="600px">';
                                                    if($order['tableNumber'] != null)
                                                    {
                                                        echo'<tr>
                                                            <td colspan="3" style="text-align:center" ><b>Table: '.$order['tableNumber'].'</b></td>
                                                        </tr>';	
                                                    }
                                                        echo'<tr>
                                                            <td style="text-align:center" ><i>Item</i></td>
                                                            <td style="text-align:center"><i>Qty.</i></td>
                                                            <td style="text-align:center"><i>Price</i></td>
                                                        </tr>';	

                                                        $orderListQuery = mysql_query("SELECT * FROM nxline_order_list WHERE order_id=" .$row['order_id']);

                                                        while($orderList=mysql_fetch_array($orderListQuery))
                                                        {
                                                            $foodOrderedQuery = mysql_query("SELECT * FROM nxline_foods WHERE food_id=".$orderList['food_id']);
                                                            $foodOrdered = mysql_fetch_assoc($foodOrderedQuery);

                                                            echo'<tr>
                                                                    <td style="text-align:center">'.$foodOrdered['food_name'].'</a></td>
                                                                    <td style="text-align:center">'. $orderList['food_quantity'].'</td>
                                                                    <td style="text-align:center">$'. $orderList['pricexquantity'].'</td>
                                                                </tr>';
                                                        }
                                                  echo '<tr></tr><tr></tr>
                                                        <tr>
                                                            <td colspan="2" style="text-align:right">Tips: </td>
                                                            <td style="text-align:center">$'.$order['tips'].'</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" style="text-align:right">Tax: </td>
                                                            <td style="text-align:center">$'.$order['tax'].'</td>    
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" style="text-align:right">Total: </td>
                                                            <td style="text-align:center">$'.$order['total_amount'].'</td>    
                                                        </tr>                                            
                                                    </table>
                                                </div>
                                                </form>
                                                
                                                <form id="assignTableForm" method="post" action="message.php?noti_id='.$row['noti_id'].'">
                                                    <table id="assignTable" style="margin:0 auto;">';
                                                        if($order['tableNumber'] == null)
                                                        {
                                                            echo'<tr>
                                                                    <td><input type = "text" id = "tableNumber" name = "tableNumber"</td>
                                                                    <td><button type="submit" name="assignTableButton" id="assignTableButton">Assign Table</button></td>
                                                                </tr>';
                                                        }
                                                        else
                                                        {
                                                            echo'<tr>
                                                                    <td>';
                                                                    ?>
                                                                    <input type="button" value="Print" onclick="printPage('payment');">
                                                                    <?php
                                                                echo'</td>
                                                                </tr>';
                                                        }
                                                    echo'</table>
                                                </form>';
                                                    
                                            echo'</form>';
                                            
                                            if(isset($_POST['assignTableButton']))
                                            {
                                                $tableNumber = $_POST['tableNumber'];
                                                
                                                mysql_query("UPDATE nxline_order SET tablenumber =".$tableNumber." WHERE order_id=".$row['order_id']);
                                                
                                                echo'<script> window.location="message.php?noti_id='.$row['noti_id'].'"; </script> ';
                                            }

                                        echo'</td>                                
                                    </tr>
                                </table>
                            </div>
                        </form>';                
                }
                else if ($userNoti['user_role_name'] == "Customer")
                {
                        echo'<form id="reserveMessageForm" method="post" action="message.php?noti_id='.$row['noti_id'].'">
                                <div id = "reserveMessage">
                                    <table id = "reserveMessageTable" width = "1000px" border="1">
                                        <tr>
                                            <th colspan="2" align="left">Your reservation at '.$shop['shop_name'].'!</th>
                                        </tr>
                                        <tr>
                                            <td align="left">
                                                <img src="' . $shop['shop_pic'] . '" alt="' . $shop['shop_name'] . '" width="20" height="20"> '
                                                . '<a href = "shop.php?shop_id='.$shop['shop_id'].'"><b>'.$shop['shop_name'].'</b></a></br>
                                                    to me.
                                            </td>
                                            <td align="right">
                                                '.$row['sendDateTime'].' 
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align = "left" style="padding:15px;"></br>
                                                <p>Hello '.$userOrder['first_name'].' '.$userOrder['last_name'].', </br>
                                                 You have a '.$order['order_type'].' food order at '.$shop['shop_name'].'! Please check the information below!</br></br> </p>
                                        
                                                <div id ="reservationInfo">
                                                    <table id="reservationInfoTable">
                                                        <tr>
                                                            <th colspan="2" align="center">Shop Information</th>
                                                            <th colspan="2" align="center">Order Information</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Name: </th>
                                                            <td>'.$shop['shop_name'].'</td>
                                                            <th>Customer Name: </th>
                                                            <td>'.$order['fullName'].'</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Address: </th>
                                                            <td>'.$shopAddr['address'].'</td>
                                                            <th>Customer Number: </th>
                                                            <td>'.$order['phoneNumber'].'</td>                                                                
                                                        </tr>
                                                        <tr>
                                                            <th>City: </th>
                                                            <td>'.$shopAddr['city'].'</td>
                                                            <th>Order Type: </th>
                                                            <td>'.$order['order_type'].'</td>
                                                        </tr>  
                                                        <tr>
                                                            <th>State: </th>
                                                            <td>'.$shopAddr['state'].' - '.$shopAddr['zip_code'].' </td>
                                                            <th>Party Size: </th>
                                                            <td>'.$order['party_size'].'</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Phone: </th>
                                                            <td>'.$shopAddr['phone'].'</td>
                                                        </tr>
                                                    </table>
                                                    </br></br>
                                                </div>
                                        
                                            <form id="reserveForm" method="post" action="message.php?noti_id='.$row['noti_id'].'">
                                                <div id="reserve">
                                                    <table id="reserveTable">';
                                                  if($order['waitTime'] == null)
                                                  {
                                                      echo''.$shop['shop_name'].' will send a wait time for you soon!';
                                                  }
                                                  else
                                                  {
                                                        echo'<tr>
                                                            <th align="center">Wait Time: &nbsp;&nbsp;</th>
                                                            <td>
                                                            <ul>
                                                                <li><label><input id="waitTime" name="waitTime" type="radio" value="'.$order['waitTime'].'"> '.$order['waitTime'].' </label> 
                                                            </ul>
                                                            </td></br></br>
                                                            </tr>';                                                      
                                                  }
                                                    echo'</table>                            
                                                    <br/><br/>
                                                </div>
                                            </form>';
                                            
                                            echo'<form id="paymentForm" method="post" action="message.php?noti_id='.$row['noti_id'].'" vertical-align="center">
                                                <div id="payment">
                                                    <table id="paymentTable" width="600px">';
                                                    if($order['tableNumber'] != null)
                                                    {
                                                        echo'<tr>
                                                            <td colspan="3" style="text-align:center" ><b>Table: '.$order['tableNumber'].'</b></td>
                                                        </tr>';	
                                                    }
                                                        echo'<tr>
                                                            <td style="text-align:center" ><i>Item</i></td>
                                                            <td style="text-align:center"><i>Qty.</i></td>
                                                            <td style="text-align:center"><i>Price</i></td>
                                                        </tr>';	

                                                        $orderListQuery = mysql_query("SELECT * FROM nxline_order_list WHERE order_id=" .$row['order_id']);

                                                        while($orderList=mysql_fetch_array($orderListQuery))
                                                        {
                                                            $foodOrderedQuery = mysql_query("SELECT * FROM nxline_foods WHERE food_id=".$orderList['food_id']);
                                                            $foodOrdered = mysql_fetch_assoc($foodOrderedQuery);

                                                            echo'<tr>
                                                                    <td style="text-align:center">'.$foodOrdered['food_name'].'</a></td>
                                                                    <td style="text-align:center">'. $orderList['food_quantity'].'</td>
                                                                    <td style="text-align:center">$'. $orderList['pricexquantity'].'</td>
                                                                </tr>';
                                                        }
                                                  echo '<tr></tr><tr></tr>
                                                        <tr>
                                                            <td colspan="2" style="text-align:right">Tips: </td>
                                                            <td style="text-align:center">$'.$order['tips'].'</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" style="text-align:right">Tax: </td>
                                                            <td style="text-align:center">$'.$order['tax'].'</td>    
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" style="text-align:right">Total: </td>
                                                            <td style="text-align:center">$'.$order['total_amount'].'</td>    
                                                        </tr>                                            
                                                    </table>
                                                </div>
                                                </form>
                                        
                                            </td>                                
                                        </tr>
                                    </table>
                                </div>
                            </form>';
                }

                if(isset($_POST["sendWaitTimeButton"]))
                {
                    $waitTime = $_POST['waitTime'];
                    
                    mysql_query("UPDATE nxline_order SET waitTime ='".$waitTime."' WHERE order_id = ".$order['order_id']);
                    
                    $customerNotiQuery = mysql_query("SELECT * FROM nxline_notification WHERE order_id =".$order['order_id']." AND user_id = ".$order['user_id']);
                    $customerNoti = mysql_fetch_assoc($customerNotiQuery);
                    
                    if($customerNoti['isRead'] == 1)
                    {
                        mysql_query("UPDATE nxline_notification SET isRead = 0 WHERE noti_id = ".$customerNoti['noti_id']);
                    }
                    
                    echo'<script> window.location="message.php?noti_id='.$row['noti_id'].'"; </script> ';
                }                
                
            }
        }
        
    }
}




require 'footer.inc.php';