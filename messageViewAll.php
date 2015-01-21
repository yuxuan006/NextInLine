<?php

require 'header.inc.php';

$result = mysql_query("SELECT * FROM nxline_notification WHERE user_id = " . mysql_real_escape_string($_GET['user_id'])." ORDER BY sendDateTime desc");

if(!$result)
{
    echo 'The notification could not be displayed, please try again later.' . mysql_error();
}
else if($user['user_id'] != $_GET['user_id'])
{
    echo 'You do not have permission to access this page!';
}
else
{
    if(mysql_num_rows($result) == 0)
    {
        echo 'You do not have any message yet!';
    }
    else
    {
        echo'<form id="allMessageForm">
            <div id = "allMessage">
                <table id = "allMessageTable">
                    <tr>
                        <td colspan="3" align="center"><b>All Messages</b></td>
                    </tr>';        
                    while($row = mysql_fetch_assoc($result))
                    {
                        $userNotiQuery = mysql_query("SELECT * FROM nxline_user WHERE user_id=".$row['user_id']);
                        $userNoti = mysql_fetch_assoc($userNotiQuery);
                        
                        if($row['reservation_id'] != 0)
                        {
                            $reservationQuery = mysql_query("SELECT * FROM nxline_reservation WHERE reservation_id =".$row['reservation_id']);
                            $reservation = mysql_fetch_assoc($reservationQuery);

                            $shopQuery = mysql_query("SELECT * FROM nxline_shop WHERE shop_id =".$reservation['shop_id']);
                            $shop = mysql_fetch_assoc($shopQuery);

                            $shopAddrQuery = mysql_query("SELECT * FROM nxline_shop_addr WHERE shop_addr_id =".$reservation['shop_addr_id']);
                            $shopAddr = mysql_fetch_assoc($shopAddrQuery);

                            $userReserveQuery = mysql_query("SELECT * FROM nxline_user WHERE user_id=".$reservation['user_id']);
                            $userReserve = mysql_fetch_assoc($userReserveQuery);

                            if($userNoti['user_role_name'] == "Owner") 
                            {
                                if($row['isRead'] == 0)
                                {
                                    echo'<tr id="messageUnread" align="left">
                                            <td><img src="' . $userReserve['user_pic'] . '" alt="' . $userReserve['user_name'] . '" width="20" height="20"><a href = "user.php?user_id='.$userReserve['user_id'].'"> &nbsp;<b>' . $userReserve['first_name'] . ' '.$userReserve['last_name'].'</b></a></td>
                                            <td><a href = "message.php?noti_id='.$row['noti_id'].'"><b>You have a table reservation from ' . $userReserve['first_name'] . ' '.$userReserve['last_name'].'!</b></a></td>
                                            <td align="right">'.$row['sendDateTime'].' &nbsp;</td>
                                        </tr>';
                                }
                                else
                                {
                                    echo'<tr id="messageRead" align="left">
                                            <td><img src="' . $userReserve['user_pic'] . '" alt="' . $userReserve['user_name'] . '" width="20" height="20"><a href = "user.php?user_id='.$userReserve['user_id'].'"> &nbsp;' . $userReserve['first_name'] . ' '.$userReserve['last_name'].'</a></td>
                                            <td><a href = "message.php?noti_id='.$row['noti_id'].'">You have a table reservation from ' . $userReserve['first_name'] . ' '.$userReserve['last_name'].'!</a></td>
                                            <td align="right">'.$row['sendDateTime'].' &nbsp;</td>
                                        </tr>';                                    
                                }
                            }
                            else if ($userNoti['user_role_name'] == "Customer")
                            {
                                if($row['isRead'] == 0)
                                {
                                    echo'<tr id="messageUnread" align="left">
                                        <td><img src="' . $shop['shop_pic'] . '" alt="' . $shop['shop_name'] . '" width="20" height="20"><a href = "shop.php?shop_id='.$shop['shop_id'].'"> &nbsp;<b>'.$shop['shop_name'].'</b></a></td>
                                        <td><a href = "message.php?noti_id='.$row['noti_id'].'"><b>Your reservation at '.$shop['shop_name'].'!</b></a></td>
                                        <td>'.$row['sendDateTime'].'</td>
                                    </tr>';
                                }
                                else
                                {
                                    echo'<tr id="messageRead" align="left">
                                        <td><img src="' . $shop['shop_pic'] . '" alt="' . $shop['shop_name'] . '" width="20" height="20"><a href = "shop.php?shop_id='.$shop['shop_id'].'"> &nbsp;'.$shop['shop_name'].'</a></td>
                                        <td><a href = "message.php?noti_id='.$row['noti_id'].'">Your reservation at '.$shop['shop_name'].'!</a></td>
                                        <td>'.$row['sendDateTime'].'</td>
                                    </tr>';                                    
                                }                            
                            }
                        }
                        else if ($row['reservation_id'] == 0)
                        {
                            $orderQuery = mysql_query("SELECT * FROM nxline_order WHERE order_id =".$row['order_id']);
                            $order = mysql_fetch_assoc($orderQuery);

                            $shopQuery = mysql_query("SELECT * FROM nxline_shop WHERE shop_id =".$order['shop_id']);
                            $shop = mysql_fetch_assoc($shopQuery);

                            $shopAddrQuery = mysql_query("SELECT * FROM nxline_shop_addr WHERE shop_addr_id =".$order['shop_addr_id']);
                            $shopAddr = mysql_fetch_assoc($shopAddrQuery);

                            $userOrderQuery = mysql_query("SELECT * FROM nxline_user WHERE user_id=".$order['user_id']);
                            $userOrder = mysql_fetch_assoc($userOrderQuery);
                            
                            if($userNoti['user_role_name'] == "Owner") 
                            {
                                if($row['isRead'] == 0)
                                {
                                    echo'<tr id="messageUnread" align="left">
                                            <td><img src="' . $userOrder['user_pic'] . '" alt="' . $userOrder['user_name'] . '" width="20" height="20"><a href = "user.php?user_id='.$userOrder['user_id'].'"> &nbsp;<b>' . $userOrder['first_name'] . ' '.$userOrder['last_name'].'</b></a></td>
                                            <td><a href = "message.php?noti_id='.$row['noti_id'].'"><b>You have a '.$order['order_type'].' food order from ' . $userOrder['first_name'] . ' '.$userOrder['last_name'].'!</b></a></td>
                                            <td align="right">'.$row['sendDateTime'].' &nbsp;</td>
                                        </tr>';
                                }
                                else
                                {
                                    echo'<tr id="messageRead" align="left">
                                            <td><img src="' . $userReserve['user_pic'] . '" alt="' . $userReserve['user_name'] . '" width="20" height="20"><a href = "user.php?user_id='.$userReserve['user_id'].'"> &nbsp;' . $userReserve['first_name'] . ' '.$userReserve['last_name'].'</a></td>
                                            <td><a href = "message.php?noti_id='.$row['noti_id'].'">You have a '.$order['order_type'].' food order from ' . $userOrder['first_name'] . ' '.$userOrder['last_name'].'!</a></td>
                                            <td align="right">'.$row['sendDateTime'].' &nbsp;</td>
                                        </tr>';                                    
                                }
                            }
                            else if ($userNoti['user_role_name'] == "Customer")
                            {
                                if($row['isRead'] == 0)
                                {
                                    echo'<tr id="messageUnread" align="left">
                                        <td><img src="' . $shop['shop_pic'] . '" alt="' . $shop['shop_name'] . '" width="20" height="20"><a href = "shop.php?shop_id='.$shop['shop_id'].'"> &nbsp;<b>'.$shop['shop_name'].'</b></a></td>
                                        <td><a href = "message.php?noti_id='.$row['noti_id'].'"><b>Your '.$order['order_type'].' food order at '.$shop['shop_name'].'!</b></a></td>
                                        <td>'.$row['sendDateTime'].'</td>
                                    </tr>';
                                }
                                else
                                {
                                    echo'<tr id="messageRead" align="left">
                                        <td><img src="' . $shop['shop_pic'] . '" alt="' . $shop['shop_name'] . '" width="20" height="20"><a href = "shop.php?shop_id='.$shop['shop_id'].'"> &nbsp;'.$shop['shop_name'].'</a></td>
                                        <td><a href = "message.php?noti_id='.$row['noti_id'].'">Your '.$order['order_type'].' food order at '.$shop['shop_name'].'!</a></td>
                                        <td>'.$row['sendDateTime'].'</td>
                                    </tr>';                                    
                                }                            
                            }                            
                        }
                    }
        echo'</table>
            </div>                              
        </form>';        
    }
}


//if(!$customerQuery || !$ownerQuery)
//{
//    echo 'The reservation could not be displayed, please try again later.' . mysql_error();
//}
//else if ($user['user_id'] != $_GET['user_id'])
//{
//    echo 'You do not have permission to access this page!';
//}
//else
//{
//    if($user['user_role_name'] == "Customer")
//    {
//        if(mysql_num_rows($customerQuery) == 0)
//        {
//            echo 'You do not have any message yet!.';
//        }
//        else
//        {   
//            if(mysql_num_rows($customerQuery))
//            {              
//                echo'<form id="allMessageForm">
//                    <div id = "allMessage">
//                        <table id = "allMessageTable">
//                            <tr>
//                                <td colspan="3" align="center"><b>All Messages</b></td>
//                            </tr>';
//                            while($customerRow = mysql_fetch_assoc($customerQuery))
//                            {
//                                $shopQuery = mysql_query("SELECT * FROM nxline_shop WHERE shop_id=".$customerRow['shop_id']);
//                                $shop = mysql_fetch_assoc($shopQuery);
//                                
//                                if($customerRow['isRead'] == 0)
//                                {
//                                    echo'<tr id="messageUnread" align="left">
//                                        <td><img src="' . $shop['shop_pic'] . '" alt="' . $shop['shop_name'] . '" width="20" height="20"><a href = "shop.php?shop_id='.$shop['shop_id'].'"> &nbsp;<b>'.$shop['shop_name'].'</b></a></td>
//                                        <td><a href = "message.php?reservation_id='.$customerRow['reservation_id'].'"><b>Your reservation at '.$shop['shop_name'].'!</b></a></td>
//                                        <td>'.$customerRow['sendDateTime'].'</td>
//                                    </tr>';
//                                }
//                                else
//                                {
//                                    echo'<tr id="messageRead" align="left">
//                                        <td><img src="' . $shop['shop_pic'] . '" alt="' . $shop['shop_name'] . '" width="20" height="20"><a href = "shop.php?shop_id='.$shop['shop_id'].'"> &nbsp;'.$shop['shop_name'].'</a></td>
//                                        <td><a href = "message.php?reservation_id='.$customerRow['reservation_id'].'">Your reservation at '.$shop['shop_name'].'!</a></td>
//                                        <td>'.$customerRow['sendDateTime'].'</td>
//                                    </tr>';                                    
//                                }
//                            }
//                        echo'</table>
//                    </div>                              
//                </form>'; 
//            }
//        }
//    }
//    else if($user['user_role_name'] == "Owner")
//    {
//        if(mysql_num_rows($ownerQuery) == 0)
//        {
//            echo 'You do not have any message yet!.';
//        }
//        else
//        {   
//            if(mysql_num_rows($ownerQuery))
//            {              
//                echo'<form id="allMessageForm">
//                    <div id = "allMessage">
//                        <table id = "allMessageTable">
//                            <tr>
//                                <td colspan="3" align="center"><b>All Messages</b></td>
//                            </tr>';
//                            while($ownerRow = mysql_fetch_assoc($ownerQuery))
//                            {
//                                $reservationQuery = mysql_query("SELECT * FROM nxline_reservation WHERE reservation_id=".$ownerRow['reservation_id']);
//                                $reservation = mysql_fetch_assoc($reservationQuery);
//                                
//                                $customerQuery = mysql_query("SELECT * FROM nxline_user WHERE user_id=".$reservation['user_id']);
//                                $customer = mysql_fetch_assoc($customerQuery);
//                                
//                                if($ownerRow['isRead'] == 0)
//                                {
//                                    echo'<tr id="messageUnread" align="left">
//                                        <td><img src="' . $customer['user_pic'] . '" alt="' . $customer['user_name'] . '" width="20" height="20"><a href = "user.php?user_id='.$customer['user_id'].'"> &nbsp;<b>' . $customer['first_name'] . ' '.$customer['last_name'].'</b></a></td>
//                                        <td><a href = "message.php?reservation_id='.$ownerRow['reservation_id'].'"><b>You have a table reservation from ' . $customer['first_name'] . ' '.$customer['last_name'].'!</b></a></td>
//                                        <td align="right">'.$ownerRow['sendDateTime'].' &nbsp;</td>
//                                    </tr>';
//                                }
//                                else
//                                {
//                                    echo'<tr id="messageRead" align="left">
//                                        <td><img src="' . $customer['user_pic'] . '" alt="' . $customer['user_name'] . '" width="20" height="20"><a href = "user.php?user_id='.$customer['user_id'].'"> &nbsp;' . $customer['first_name'] . ' '.$customer['last_name'].'</a></td>
//                                        <td><a href = "message.php?reservation_id='.$ownerRow['reservation_id'].'">You have a table reservation from ' . $customer['first_name'] . ' '.$customer['last_name'].'!</a></td>
//                                        <td align="right">'.$ownerRow['sendDateTime'].' &nbsp;</td>
//                                    </tr>';                                    
//                                }
//                            }
//                        echo'</table>
//                    </div>                              
//                </form>'; 
//            }
//        }
//    }    
//    
//}

require 'footer.inc.php';