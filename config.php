<?php

$dbUser = "nextinl2_hoangn";
$dbPass = "123@next";
$dbDatabase = "nextinl2_main";
$dbHost = "localhost";      

$dbConn = mysql_connect($dbHost, $dbUser, $dbPass);

if ($dbConn) {
    mysql_select_db($dbDatabase); 
//    print("Successfully connected to the database"); 
} else {
    die("<strong>Error:</strong> Could not connect to database.");
}
 

function getAddrTotalReview($shop_addr_id)
{
    $queryAddrRate = mysql_query("SELECT * FROM nxline_review WHERE shop_addr_id = " . $shop_addr_id);
    return $totalAddrReview = mysql_num_rows($queryAddrRate);
}

function getAddrRating($shop_addr_id)
{
    $queryAddrRate = mysql_query("SELECT * FROM nxline_review WHERE shop_addr_id = " . $shop_addr_id);
    $totalAddrReview = (mysql_num_rows($queryAddrRate) + 1);

    $sumStarAddrQuery = mysql_query("SELECT shop_addr_id, SUM(star) AS totalAddrStar FROM nxline_review WHERE shop_addr_id= " . $shop_addr_id);
    $sumStarAddr = mysql_fetch_array($sumStarAddrQuery);

    $addrRating = ($sumStarAddr['totalAddrStar'] + 3) / $totalAddrReview;
    
    return number_format((float)$addrRating, 1, '.', '');
}

function getShopTotalReview($shop_id)
{
    $queryShopRate = mysql_query("SELECT * FROM nxline_review WHERE shop_id = " . $shop_id);
    return $totalShopReview = mysql_num_rows($queryShopRate);
}

function getShopRating($shop_id)
{
    $totalAddrQuery = mysql_query("SELECT shop_addr_id FROM nxline_shop_addr WHERE shop_id =".$shop_id);
    $totalAddr = mysql_num_rows($totalAddrQuery);
    
    $queryShopRate = mysql_query("SELECT * FROM nxline_review WHERE shop_id = " . $shop_id);
    $totalShopReview = (mysql_num_rows($queryShopRate) + $totalAddr);

    $sumStarShopQuery = mysql_query("SELECT shop_id, SUM(star) AS totalShopStar FROM nxline_review WHERE shop_id= " . $shop_id);
    $sumStarShop = mysql_fetch_array($sumStarShopQuery);

    $shopRating = ($sumStarShop['totalShopStar'] + 3*$totalAddr) / $totalShopReview;
    
    return number_format((float)$shopRating, 1, '.', '');
}

function getTax ($order_id)
{
    $totalAmountQuery = mysql_query("SELECT SUM(pricexquantity) AS totalAmount FROM nxline_order_list WHERE order_id=". $order_id);
    $totalAmount = mysql_fetch_assoc($totalAmountQuery);    
    
    $tax = 0.07*$totalAmount['totalAmount']; 
    
    return number_format((float)$tax, 2, '.', '');
}

function getTotalAmount ($order_id)
{
    $totalAmountQuery = mysql_query("SELECT SUM(pricexquantity) AS totalAmount FROM nxline_order_list WHERE order_id=". $order_id);
    $totalAmount = mysql_fetch_assoc($totalAmountQuery);    
    
    $total = $totalAmount['totalAmount'] + 0.07*$totalAmount['totalAmount'];
    
    return number_format((float)$total, 2, '.', '');
}
?>


