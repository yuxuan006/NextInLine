<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="A short description." />
    <meta name="keywords" content="put, keywords, here" />
    <title>NextInLine</title>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
    <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
   
    <script src="js/jquery-1.6.1.min.js"></script>
    <script src="js/jquery.nivo.slider.pack.js"></script>
    <script src="js/blanka.js"></script>
    <link rel="stylesheet" href="css/nivo-slider.css" type="text/css">
    <style type="text/css">

    .custom-date-style {
            background-color: red !important;
    }

    </style>
    
    <script LANGUAGE="javascript">
           
            function openwin(){
                window.open("contact_us.php","newwindow","width=550,height=450,top=300,left=500,toolbar=no,scrillbars=no,resizable=no,location=o,status=no")
            }
            
        </script>
        
</head>
<body>
    
    <?php  
    require("function.php");
    
    $user = getUser();
            
    $receiveReserveQuery = mysql_query("SELECT * FROM nxline_receive_reserve WHERE user_id =".$user['user_id']." ORDER BY sendDateTime desc LIMIT 5"); 
            
    $countCusReserveQuery = mysql_query("SELECT * FROM nxline_reservation WHERE isRead = 0 AND user_id =".$user['user_id']);
            
    $CusReserveQuery= mysql_query("SELECT * FROM nxline_reservation WHERE user_id =".$user['user_id']." ORDER BY sendDateTime desc LIMIT 5");
            
    $notiQuery = mysql_query("SELECT * FROM nxline_notification WHERE user_id =".$user['user_id']." ORDER BY sendDateTime desc LIMIT 5");
    
    if(!isLoggedIn())
    {
    ?>
    
    <div class="header">
            <div class="wrap">
                    <a href="index.php" class="logo"></a>
                    <ul class="navigation clearfix">
                            <li>
                                <a href="#" class="login-button">Login</a>

                                <div class="overlay"></div><!--overlay-->

                                <form class="login" method="post" action="./login.php">
                                    <div class="login-content">
                                        <a href="#" class="close">x</a>
                                        <label for="name">Username:</label>
                                        <input type="text" name="username" id="username" placeholder="Username"/>
                                        <label for="pass">Password:</label>
                                        <input type="password" name="password" id="password" placeholder="Password"/>
                                        <input type="submit" name="loginButton" id="loginButton" value="Log In" class="loginsubmit"/>
                                    </div>
                                </form><!--login box-->
                            </li>
                            <li>
                                    <a href="register.php">Register</a>
                            </li>
                    </ul><!--end navigation-->
            </div>
    </div><!--end header--> 
    
    <?php }
    else
    {?>
    
    <div class="header">
            <div class="wrap">
                    <a href="index.php" class="logo"></a>
                    <ul class="navigation clearfix">
                            <li>                                
                                <a href= "user.php?user_id= <?php echo' '.$user['user_id'].' '?>"> <img src="<?php echo' '.$user['user_pic'].' '?>" alt="<?php echo' '.$user['user_name'].' '?>" width="40" height="40"> <?php echo' '.$user['first_name'].' ' .$user['last_name'].' '?></a>

                                <div class="overlay"></div><!--overlay-->
                            </li>
                            <li>
                                <a href="#">
                                    <img src="./img/noti.png" style="width: 30px;" />
                                    <?php    
                                        $countNotiQuery = mysql_query("SELECT * FROM nxline_notification WHERE user_id =".$user['user_id']." AND isRead = 0"); 
                                        $notiCount = mysql_num_rows($countNotiQuery);  
                                        if($notiCount != 0)
                                        {
                                            echo '<label id="mes">'.$notiCount.'</label>';
                                        }
                                    ?>
                                </a>
                                            <ul class="sub-notiMenu">
                                                <li class="egg">
                                                    <div class="toppointer"><img src="./img/top.png" /></div>
                                                        <div id="two_comments" class="content">
                                                            <?php
                                                        if(mysql_num_rows($notiQuery) != 0)
                                                        {
                                                            while($noti = mysql_fetch_assoc($notiQuery))
                                                            {                                                                
                                                                if ($noti['reservation_id'] != 0 && $noti['order_id'] != 0)
                                                                {
                                                                    $listsql=mysql_query("SELECT * FROM nxline_order WHERE order_id = ".$noti['order_id']);
                                                                    $rowsmall = mysql_fetch_assoc($listsql);   
                                                                    
                                                                    $userReserveQuery = mysql_query("SELECT * FROM nxline_user WHERE user_id =".$rowsmall['user_id']);
                                                                    $userReserve=  mysql_fetch_assoc($userReserveQuery);                                                                    
                                                                    
                                                                    $userOrderQuery = mysql_query("SELECT * FROM nxline_user WHERE user_id =".$rowsmall['user_id']);
                                                                    $userOrder =  mysql_fetch_assoc($userOrderQuery);

                                                                    $shopQuery = mysql_query("SELECT * FROM nxline_shop WHERE shop_id =".$rowsmall['shop_id']);
                                                                    $shop = mysql_fetch_assoc($shopQuery);
                                                                    
                                                                    if($user['user_role_name'] == "Owner")
                                                                    {
                                                                        if($noti['isRead'] == 1)
                                                                        {                                                                
                                                                            echo'<a href = "message.php?noti_id='.$noti['noti_id'].'" >
                                                                                <div class="comment_ui">
                                                                                <div class="comment_text">

                                                                                    <div  class="comment_actual_text">
                                                                                        <img src="' . $userReserve['user_pic'] . '" alt="' . $userReserve['user_name'] . '" width="20" height="20"> '.$userReserve['first_name'].' '.$userReserve['last_name'].'
                                                                                            has reserved a table and order food at '.$shop['shop_name'].' !
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            </a>';
                                                                        }
                                                                        else if ($noti['isRead'] == 0)
                                                                        {
                                                                            echo'<a href = "message.php?noti_id='.$noti['noti_id'].'" >
                                                                                <div class="comment_ui_unRead">
                                                                                <div class="comment_text">

                                                                                    <div  class="comment_actual_text">
                                                                                        <img src="' . $userReserve['user_pic'] . '" alt="' . $userReserve['user_name'] . '" width="20" height="20"> '.$userReserve['first_name'].' '.$userReserve['last_name'].'
                                                                                            has reserved a table and order food at '.$shop['shop_name'].' !
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            </a>';                                                                
                                                                        }
                                                                    }
                                                                    else if ($user['user_role_name'] == "Customer")
                                                                    {
                                                                        if($noti['isRead'] == 1)
                                                                        {                                                                
                                                                            echo'<a href = "message.php?noti_id='.$noti['noti_id'].'" >
                                                                                <div class="comment_ui">
                                                                                <div class="comment_text">

                                                                                    <div  class="comment_actual_text">
                                                                                        <img src="' . $shop['shop_pic'] . '" alt="' . $shop['shop_name'] . '" width="20" height="20"> '.$shop['shop_name'].': '
                                                                                    . 'You have a reservation and food order at '.$shop['shop_name'].'!
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            </a>';
                                                                        }
                                                                        else if ($noti['isRead'] == 0)
                                                                        {
                                                                            echo'<a href = "message.php?noti_id='.$noti['noti_id'].'" >
                                                                                <div class="comment_ui_unRead">
                                                                                <div class="comment_text">

                                                                                    <div  class="comment_actual_text">
                                                                                        <img src="' . $shop['shop_pic'] . '" alt="' . $shop['shop_name'] . '" width="20" height="20"> '.$shop['shop_name'].': '
                                                                                     . 'You have a reservation and food order at '.$shop['shop_name'].'!
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            </a>';  
                                                                        }
                                                                    }                                                                    
                                                                }                                                                
                                                                else if($noti['reservation_id'] != 0)
                                                                {
                                                                    $listsql=mysql_query("SELECT * FROM nxline_reservation WHERE reservation_id = ".$noti['reservation_id']);
                                                                    $rowsmall = mysql_fetch_assoc($listsql);                                                                    
                                                                    
                                                                    $userReserveQuery = mysql_query("SELECT * FROM nxline_user WHERE user_id =".$rowsmall['user_id']);
                                                                    $userReserve=  mysql_fetch_assoc($userReserveQuery);

                                                                    $shopQuery = mysql_query("SELECT * FROM nxline_shop WHERE shop_id =".$rowsmall['shop_id']);
                                                                    $shop = mysql_fetch_assoc($shopQuery);
                                                                    
                                                                    if($user['user_role_name'] == "Owner")
                                                                    {
                                                                        if($noti['isRead'] == 1)
                                                                        {                                                                
                                                                            echo'<a href = "message.php?noti_id='.$noti['noti_id'].'" >
                                                                                <div class="comment_ui">
                                                                                <div class="comment_text">

                                                                                    <div  class="comment_actual_text">
                                                                                        <img src="' . $userReserve['user_pic'] . '" alt="' . $userReserve['user_name'] . '" width="20" height="20"> '.$userReserve['first_name'].' '.$userReserve['last_name'].'
                                                                                            has reserved a table at '.$shop['shop_name'].' !
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            </a>';
                                                                        }
                                                                        else if ($noti['isRead'] == 0)
                                                                        {
                                                                            echo'<a href = "message.php?noti_id='.$noti['noti_id'].'" >
                                                                                <div class="comment_ui_unRead">
                                                                                <div class="comment_text">

                                                                                    <div  class="comment_actual_text">
                                                                                        <img src="' . $userReserve['user_pic'] . '" alt="' . $userReserve['user_name'] . '" width="20" height="20"> '.$userReserve['first_name'].' '.$userReserve['last_name'].'
                                                                                            has reserved a table at '.$shop['shop_name'].' !
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            </a>';                                                                
                                                                        }
                                                                    }
                                                                    else if ($user['user_role_name'] == "Customer")
                                                                    {
                                                                        if($noti['isRead'] == 1)
                                                                        {                                                                
                                                                            echo'<a href = "message.php?noti_id='.$noti['noti_id'].'" >
                                                                                <div class="comment_ui">
                                                                                <div class="comment_text">

                                                                                    <div  class="comment_actual_text">
                                                                                        <img src="' . $shop['shop_pic'] . '" alt="' . $shop['shop_name'] . '" width="20" height="20"> '.$shop['shop_name'].': You have reserved a table at '.$shop['shop_name'].'!
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            </a>';
                                                                        }
                                                                        else if ($noti['isRead'] == 0)
                                                                        {
                                                                            echo'<a href = "message.php?noti_id='.$noti['noti_id'].'" >
                                                                                <div class="comment_ui_unRead">
                                                                                <div class="comment_text">

                                                                                    <div  class="comment_actual_text">
                                                                                        <img src="' . $shop['shop_pic'] . '" alt="' . $shop['shop_name'] . '" width="20" height="20"> '.$shop['shop_name'].': You have reserved a table at '.$shop['shop_name'].'!
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            </a>';                                                                
                                                                        }
                                                                    }
                                                                }
                                                                else if ($noti['reservation_id'] == 0)
                                                                {
                                                                    $listsql=mysql_query("SELECT * FROM nxline_order WHERE order_id = ".$noti['order_id']);
                                                                    $rowsmall = mysql_fetch_assoc($listsql);                                                                     
                                                                    
                                                                    $userOrderQuery = mysql_query("SELECT * FROM nxline_user WHERE user_id =".$rowsmall['user_id']);
                                                                    $userOrder =  mysql_fetch_assoc($userOrderQuery);

                                                                    $shopQuery = mysql_query("SELECT * FROM nxline_shop WHERE shop_id =".$rowsmall['shop_id']);
                                                                    $shop = mysql_fetch_assoc($shopQuery);
                                                                    
                                                                    if($user['user_role_name'] == "Owner")
                                                                    {
                                                                        if($noti['isRead'] == 1)
                                                                        {                                                                
                                                                            echo'<a href = "message.php?noti_id='.$noti['noti_id'].'" >
                                                                                <div class="comment_ui">
                                                                                <div class="comment_text">

                                                                                    <div  class="comment_actual_text">
                                                                                        <img src="' . $userOrder['user_pic'] . '" alt="' . $userOrder['user_name'] . '" width="20" height="20"> '.$userOrder['first_name'].' '.$userOrder['last_name'].'
                                                                                            has a '.$rowsmall['order_type'].' food order at '.$shop['shop_name'].' !
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            </a>';
                                                                        }
                                                                        else if ($noti['isRead'] == 0)
                                                                        {
                                                                            echo'<a href = "message.php?noti_id='.$noti['noti_id'].'" >
                                                                                <div class="comment_ui_unRead">
                                                                                <div class="comment_text">

                                                                                    <div  class="comment_actual_text">
                                                                                        <img src="' . $userOrder['user_pic'] . '" alt="' . $userOrder['user_name'] . '" width="20" height="20"> '.$userOrder['first_name'].' '.$userOrder['last_name'].'
                                                                                            has a '.$rowsmall['order_type'].' food order at '.$shop['shop_name'].' !
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            </a>';                                                                
                                                                        }
                                                                    }
                                                                    else if ($user['user_role_name'] == "Customer")
                                                                    {
                                                                        if($noti['isRead'] == 1)
                                                                        {                                                                
                                                                            echo'<a href = "message.php?noti_id='.$noti['noti_id'].'" >
                                                                                <div class="comment_ui">
                                                                                <div class="comment_text">

                                                                                    <div  class="comment_actual_text">
                                                                                        <img src="' . $shop['shop_pic'] . '" alt="' . $shop['shop_name'] . '" width="20" height="20"> '.$shop['shop_name'].': '
                                                                                    . 'You have a '.$rowsmall['order_type'].' food order at '.$shop['shop_name'].'!
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            </a>';
                                                                        }
                                                                        else if ($noti['isRead'] == 0)
                                                                        {
                                                                            echo'<a href = "message.php?noti_id='.$noti['noti_id'].'" >
                                                                                <div class="comment_ui_unRead">
                                                                                <div class="comment_text">

                                                                                    <div  class="comment_actual_text">
                                                                                        <img src="' . $shop['shop_pic'] . '" alt="' . $shop['shop_name'] . '" width="20" height="20"> '.$shop['shop_name'].': '
                                                                                    . 'You have a '.$rowsmall['order_type'].' food order at '.$shop['shop_name'].'!
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            </a>';  
                                                                        }
                                                                    }
                                                                }                                                                
                                                            }
                                                        }
                                                        else
                                                        {
                                                            echo'<div class="comment_ui_unRead">
                                                                <div class="comment_text">
                                                                    <div  class="comment_actual_text">
                                                                        Empty!
                                                                    </div>
                                                                </div>
                                                            </div>';                                                                 
                                                        }

                                                    echo'</div>
                                                        <div class="bbbbbbb" id="view">
                                                            <div style="background-color: #F7F7F7; border-bottom-left-radius: 3px; border-bottom-right-radius: 3px; position: relative; z-index: 100; padding:8px; cursor:pointer;">
                                                            <a href="messageViewAll.php?user_id='.$user['user_id'].'" class="view_comments">View all messages</a>';
                                                            ?>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>                                
                            </li>
                            <li>
                                <form id="logoutForm" method="post" action="./index.php">
                                <input type="submit" name="logoutButton" id="logoutButton" value="Log Out" class="loginsubmit"/>
                                </form>
                            </li>
                    </ul><!--end navigation-->
            </div>
    </div><!--end header-->         
    
    <?php 
        if (isset($_POST['logoutButton'])) 
        {
            mysql_query("DELETE FROM nxline_sessions WHERE user_id = " . (int) $user['user_id']);

            echo'<script> window.location="./index.php"; </script> ';
        }    
    } ?>
    
    <div class="hiddenHeader"></div>
    
    <div id="wrapper">
        
        <div id="content">    