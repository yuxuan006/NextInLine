<?php
require("header.inc.php");

$result = mysql_query("SELECT * FROM nxline_shop_addr WHERE shop_addr_id = " . mysql_real_escape_string($_GET['shop_addr_id']));

if (!$result) {
    echo 'The address could not be displayed, please try again later.' . mysql_error();
} else {
    if (mysql_num_rows($result) == 0) {
        echo 'This address does not exist.';
    } else {
        //display category data
        while ($row = mysql_fetch_assoc($result)) 
        {
            if (!isLoggedIn()) 
            {
                header("Location: ./login.php");
            } 
            else 
            {
                if(isset($_POST['sendReserveButton']))
                {
                    $fullName = $_POST['fullName'];
                    $phoneNumber = $_POST['phoneNumber'];
                    $dateString = $_POST['datepicker'];
                    $date = substr($dateString, -10);
                    $timeString = $_POST['timepicker'];
                    $time = date('H:i:s', strtotime($timeString));
                    $partySize = $_POST['partySize'];
                    $sendDateTime = date("Y-m-d H:i:s");
                    
                    $shopQuery = mysql_query("SELECT * FROM nxline_shop WHERE shop_id= ".$row['shop_id']);
                    $shop = mysql_fetch_assoc($shopQuery);                       
                    
                    $numReservationQuery = mysql_query("SELECT * FROM nxline_reservation "
                        . "WHERE shop_addr_id= ".$row['shop_addr_id']." AND reserve_date = STR_TO_DATE('".$date."', '%m/%d/%Y') AND reserve_time = STR_TO_DATE('".$timeString."', '%H:%i:%s')");
                    $numReservation = mysql_num_rows($numReservationQuery);
                    
                    $user= getUser();
                    
                    $repeateReserveQuery = mysql_query("SELECT * FROM nxline_reservation "
                        . "WHERE shop_addr_id= ".$row['shop_addr_id']." AND reserve_date = STR_TO_DATE('".$date."', '%m/%d/%Y') AND reserve_time = STR_TO_DATE('".$timeString."', '%H:%i:%s') AND user_id = ".$user['user_id']);
                    $repeateReserve = mysql_fetch_assoc($repeateReserveQuery);
                    
                    if ($fullName == "")
                    {
                        echo'Please enter your Full Name';
                    }
                    else if($phoneNumber == "")
                    {
                        echo'Please enter your phone number';
                    }
                    else if($date == "")
                    {
                        echo'Please select the date! </br>';
                    }
                    else if ($time == "")
                    {
                        echo'Please select time! </br>';
                    }
                    else if ($partySize == "")
                    {
                        echo'Please type in the number of people are going! </br>';
                    }
                    else if(mysql_num_rows($repeateReserveQuery) != 0)
                    {
                        echo'You already reserve a table at '.$shop['shop_name'].' on '.$date.' at '.$time.'! Please choose a different date and time to make another reservation!';
                    }
                    else if ($numReservation < $row['numReserve'])
                    {
                            $sessionQuery = mysql_query("SELECT * FROM nxline_sessions WHERE user_id =".$user['user_id']);
                            $session = mysql_fetch_assoc($sessionQuery);

                            mysql_query("INSERT INTO nxline_reservation(party_size, user_id, shop_id, idSession, shop_addr_id, reserve_date, reserve_time, sendDateTime, phoneNumber, fullName)"
                                    . "VALUES (".(int)$partySize.", ".$user['user_id'].", ".$row['shop_id'].", ".$session['id'].", ".$row['shop_addr_id'].", STR_TO_DATE('".$date."', '%m/%d/%Y'), STR_TO_DATE('".$timeString."', '%H:%i:%s'), '".$sendDateTime."', '".$phoneNumber."', '".$fullName."'  )  ");

                            $reservationQuery = mysql_query("SELECT * FROM nxline_reservation WHERE user_id =".$user['user_id']." AND shop_id = ".$row['shop_id']." AND idSession =" .$session['id']." AND shop_addr_id=".$row['shop_addr_id']." AND sendDateTime = '".$sendDateTime."'" );
                            $reservation = mysql_fetch_assoc($reservationQuery);

                            $shopQuery = mysql_query("SELECT * FROM nxline_shop WHERE shop_id= ".$row['shop_id']);
                            $shop = mysql_fetch_assoc($shopQuery);

                            mysql_query("INSERT INTO nxline_notification (user_id, reservation_id, sendDateTime)"
                                    . "VALUES (".$user['user_id'].", ".$reservation['reservation_id'].", '".$sendDateTime."') ");
                            
                            mysql_query("INSERT INTO nxline_notification (user_id, reservation_id, sendDateTime)"
                                    . "VALUES (".$shop['user_id'].", ".$reservation['reservation_id'].", '".$sendDateTime."') ");
                            
                            $notiQuery = mysql_query("SELECT MAX(noti_id) AS current_noti_id FROM nxline_notification WHERE user_id=".$user['user_id']);
                            $noti = mysql_fetch_assoc($notiQuery);

                            echo'<script> window.location="message.php?noti_id='.$noti['current_noti_id'].'"; </script> ';
                    }
                    else
                    {
                        echo'<form id="reserveForm" method="post" action="reserveSending.php?shop_addr_id='.$row['shop_addr_id'].'">
                                <div id="reserve">
                                    <table id="reserveTable">
                                        <tr>
                                            <th align="center">Another Available Time for You: &nbsp;&nbsp;</th>
                                            <td><ul>';
                                                for($x = -2 ;$x <= 2; $x++ )
                                                {
                                                    $selectedTime = date('H:i:s', strtotime($timeString.'+'.$x.' hour'));
                                                    $displayTime = date('H:i', strtotime($timeString.'+'.$x.' hour'));
                                                    
                                                    $numReservationQuery = mysql_query("SELECT * FROM nxline_reservation "
                                                            . "WHERE shop_addr_id= ".$row['shop_addr_id']." AND reserve_date = STR_TO_DATE('".$date."', '%m/%d/%Y') AND reserve_time = STR_TO_DATE('".$selectedTime."', '%H:%i:%s')");
                                                    $numReservation = mysql_num_rows($numReservationQuery);
                                                    
                                                    if($numReservation < $row['numReserve'])
                                                    {
                                                        if(substr($dateString, 0,3) == "Sun" || substr($dateString, 0,3) == "Sat")
                                                        {
                                                            if($selectedTime <= "20:00:00" && $selectedTime >= "11:00:00")
                                                            {
                                                                echo'<li><label><input id="pickedTime" name="pickedTime" type="radio" value="'.$selectedTime.'"> '.$displayTime.'</label></li>';
                                                            }                                                            
                                                        }
                                                        else
                                                        {
                                                            if($selectedTime <= "22:00:00" && $selectedTime >= "08:00:00")
                                                            {
                                                                echo'<li><label><input id="pickedTime" name="pickedTime" type="radio" value="'.$selectedTime.'"> '.$displayTime.'</label></li>';
                                                            }
                                                        }
                                                    }                                                                                         
                                                }                    
                                        echo'</ul></td></br></br>
                                        </tr>
                                        </tr>
                                            <td><input type="hidden" id="fullName" name="fullName" type="text" value="'.$fullName.'"></td>
                                            <td><input type="hidden" id="phoneNumber" name="phoneNumber" type="text" value="'.$phoneNumber.'"></td>
                                            <td><input type="hidden" id="datePost" name="datePost" value="'.$date.'"></td>
                                            <td><input type="hidden" id="partySizePost" name="partySizePost" value="'.$partySize.'"></td>
                                        </tr>
                                        
                                        <tr>
                                            <td align="center" colspan="2"><button type="submit" name="makeReserveButton" id="makeReserveButton">Make a Reservation</button></td>
                                        </tr>
                                    </table>                            
                                    <br/><br/>
                                </div>
                            </form>';
                                        
                        require("footer.inc.php");                
                        exit();
                    }
                }

                    $shopQuery = mysql_query("SELECT * FROM nxline_shop WHERE shop_id= ".$row['shop_id']);
                    $shop = mysql_fetch_assoc($shopQuery);                    
                    
                    echo'<form id="reserveForm" method="post" action="reserve.php?shop_addr_id='.$row['shop_addr_id'].'">
                            <div id="reserve">
                                <table id="reserveTable">
                                    <tr>
                                        <th colspan ="4" align="center">'.$shop['shop_name'].'</th>
                                    </tr>
                                    <tr>
                                        <td>Full Name: </td>
                                        <td><input id="fullName" name="fullName" type="text"></td>
                                    </tr>
                                    <tr>
                                        <td>Phone Number: </td>
                                        <td><input id="phoneNumber" name="phoneNumber" type="text"></td>
                                    </tr>                                    
                                    <tr>
                                        <td>Date: </td>
                                        <td><input id="datepicker" name="datepicker" type="text" readonly="readonly" placeholder="'.date("l: d/m/Y").'"></td>
                                        <td>Time: </td>
                                        <td><input id="timepicker" name="timepicker" type="text" readonly="readonly" placeholder="'.date("H:i").'"></td>
                                    </tr>
                                    <tr>
                                        <td>Party Size: </td>
                                        <td><input id="partySize" name="partySize" type="text" placeholder="E.g: 5"></td></br></br>                                      
                                    </tr>
                                    <tr>
                                        <td colspan ="4" align="center"><button type="submit" name="sendReserveButton" id="sendReserveButton">Make a Reservation</button></td>  
                                    </tr>
                                    
                                </table>                            
                                <br/><br/>
                            </div>
                        </form>'; 
            }
        }
    }
}


require("footer.inc.php");