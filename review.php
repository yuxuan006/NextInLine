<?php

require("header.inc.php");

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
            $query = mysql_query("SELECT * FROM nxline_shop WHERE shop_id=" .$row['shop_id']);            
            $shop = mysql_fetch_assoc($query);
            $shopOwner = mysql_query("SELECT * FROM nxline_user WHERE user_id=".$shop['user_id']);
            $owner = mysql_fetch_assoc($shopOwner);

            echo'<form id="shopInfoForm" method="post" action="review.php?shop_addr_id='. $row['shop_addr_id'].'">
                    <div id="shopInfo">
                            <table id="shopInfoTable">
                                    <tr>
                                            <th  colspan="3"><a href="shop.php?shop_id='.$row['shop_id'].'">'.$shop['shop_name'].'</a></th>
                                    </tr>
                                    <tr>
                                            <td width="100px">'.getAddrRating($row['shop_addr_id']).'</td>
                                            <td width="300px">'.getAddrTotalReview($row['shop_addr_id']).' reviews</td>
                                    </tr>
                                    <tr>
                                            <td rowspan="3" width="100px"><img src="'.$shop['shop_pic'].'" alt="'. $shop['shop_name'].'"></td> 
                                    </tr>
                                    <tr>
                                            <td width="300px">'. $row['address'].' <br/>'.$row['city'].' '.$row['state'].' '.$row['zip_code'].' <br/>'.$row['country'].'</td>
                                    </tr>
                                    <tr>
                                            <td width="300px">'.$row['phone'].'</td>
                                            <td><a href="user.php?user_id='.$owner['user_id'].'">Owner: '.$owner['first_name'].' '.$owner['last_name'].'</a></td>
                                    </tr>
                            </table>
                    </div>
            </form>
            <br/>';
            
            $reviewListQuery = mysql_query("SELECT * FROM nxline_review WHERE shop_addr_id = " . $row['shop_addr_id']);

            echo "Reviews";                    

            while($reviewList = mysql_fetch_array($reviewListQuery))
            {              	    
          	    $userReviewQuery=mysql_query("SELECT * FROM nxline_user WHERE user_id=" .$reviewList['user_id']);

              	    $userReview=mysql_fetch_assoc($userReviewQuery);       			

                    echo'<form id="reviewListForm" method="post" action="review.php?shop_addr_id='. $row['shop_addr_id'].'">
                            <div id="reviewList">
                                    <table id="reviewListTable">
                                            <tr>
                                    		<td rowspan="2" style="text-align:center;vertical-align:top;padding:0" width="100px"><img src="'.$userReview['user_pic'].'" alt="'. $userReview['user_name'].'"></td>
                                                <td rowspan="2" style="text-align:left;vertical-align:top;padding:0" width="150px" border-right= "1px solid black">
                                                    <a href="user.php?user_id=' . $userReview['user_id'] . '"><b>'.$userReview['first_name'].' '.$userReview['last_name'].'</b></a><br/>
                                                    '.$userReview['sex'].'<br/>
                                                    '.$userReview['cell_phone'].'    
                                                </td>
                                                <td height="10">'.$reviewList['star'].'</td>
                                                <td height="10">'.$reviewList['review_date'].'</td>';
                                                if($reviewList['user_id'] == $user['user_id'])
                                                {
                                                    echo'<td height="10"><a href="reviewEdit.php?review_id='.$reviewList['review_id'].'">Edit</a></td>';
                                                }
                                            echo'</tr>
                                            <tr>
                                                <td colspan="2" style="text-align:left;vertical-align:top;padding:0" >'.$reviewList['content'].'</td>
                                            </tr>
                                       </table>
                            </div>
                    </form>';
            }           
            
            if(!isLoggedIn())
            {
                echo'Please <a href = login/php>login</a> first to write a review!';
            }
            else
            {
                $reviewQuery = mysql_query("SELECT * FROM nxline_review WHERE shop_addr_id = " . $row['shop_addr_id']." AND user_id = ".$user['user_id']);
                $review = mysql_fetch_assoc($reviewQuery);
                
                
                if($review['isReviewed'] == 1)
                {
                    echo'You already reviewd! </br>';
                }
                else
                {
                    echo'<form id="writeReviewForm" method="post" action="review.php?shop_addr_id='.$row['shop_addr_id'].'">
                                <div id="writeReview">
                                        <table id="writeReviewTable">
                                                <tr>
                                                    <th colspan ="2" align="center">Review</th>
                                                </tr>
                                                <tr>
                                                    <td>Rating: </td>
                                                    <td><select name="rating" id ="rating">
                                                        <option value="">--Select--</option>
                                                        <option value="1">1 star</option>
                                                        <option value="2">2 stars</option>
                                                        <option value="3">3 stars</option>
                                                        <option value="4">4 stars</option>
                                                        <option value="5">5 stars</option>                                                        
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Review: </td>
                                                    <td><textarea rows="4" cols="50" name="content" id="content" placeholder="Enter text here..."></textarea></td>
                                                </tr>
                                                <tr>
                                                    <td colspan ="2" align="center"><button type="submit" name="postRevButton" id="postRevButton">Post Review</button></td>
                                                </tr>
                                        </table>
                                </div>
                        </form>
                        <br/>';                    
                }
            }

            if (isset($_POST['postRevButton'])) {
                $rating = $_POST['rating'];
                $content = $_POST['content'];
                $date = date('l\, F jS \, Y');

                if ($rating == "") {
                    echo'Please rate the shop! <br/>';
                }
                else if($content == "")
                {
                    echo'Please write your review! <br/>';
                }
                else {
                    //rating for each address
                    mysql_query("INSERT INTO nxline_review (star, review_date, user_id, shop_id, shop_addr_id, content, isReviewed) "
                            . "VALUES (" . $rating . ", '" . $date . "', " . (int) $user['user_id']. ", " . (int) $shop['shop_id'] . ", " . (int) $row['shop_addr_id'] . ", '" . mysql_escape_string($content) . "', 1)");
                                        
                    //rating for shop based on rating of each address
                    
                    mysql_query("UPDATE nxline_shop SET star =".getShopRating($row['shop_id']).", total_review = ".getShopTotalReview($row['shop_id'])." WHERE shop_id =".$row['shop_id']);
                    
                    echo'<script> window.location="review.php?shop_addr_id='.$row['shop_addr_id'].'"; </script> ';               
                }
            }                                      
        }
    }
}


require("footer.inc.php");