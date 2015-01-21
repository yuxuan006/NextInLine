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
                if(isset($_POST['makeReserveButton']))
                {
                    $fullName = $_POST['fullName'];
                    $phoneNumber = $_POST['phoneNumber'];
                    $datePost = $_POST['datePost'];
                    $pickedTime = $_POST['pickedTime'];
                    $partySizePost = $_POST['partySizePost'];
                    $sendDateTime = date("Y-m-d H:i:s");

                    $sessionQuery = mysql_query("SELECT * FROM nxline_sessions WHERE user_id =".$user['user_id']);
                    $session = mysql_fetch_assoc($sessionQuery);

                    mysql_query("INSERT INTO nxline_reservation(party_size, user_id, shop_id, idSession, shop_addr_id, reserve_date, reserve_time, sendDateTime, phoneNumber, fullName)"
                        . "VALUES (".(int)$partySizePost.", ".$user['user_id'].", ".$row['shop_id'].", ".$session['id'].", ".$row['shop_addr_id'].", STR_TO_DATE('".$datePost."', '%m/%d/%Y'), '".$pickedTime."', '".$sendDateTime."', '".$phoneNumber."', '".$fullName."'  )  ");
                    
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
            }
        }
    }
}



require("footer.inc.php");

