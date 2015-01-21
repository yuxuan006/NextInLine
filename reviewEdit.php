<?php

require("header.inc.php");

$result = mysql_query("SELECT * FROM nxline_review WHERE review_id = " . mysql_real_escape_string($_GET['review_id']));

if(!$result)
{
    echo 'The review could not be displayed, please try again later.' . mysql_error();
}
else
{
    if(mysql_num_rows($result) == 0)
    {
        echo 'This review does not exist.';
    }
    else
    {
        //display category data
        while($row = mysql_fetch_assoc($result))
        {
            if($row['user_id'] == $user['user_id'])
            {
                    echo'<form id="writeReviewForm" method="post" action="reviewEdit.php?review_id='.$row['review_id'].'">
                                <div id="writeReview">
                                        <table id="writeReviewTable">
                                                <tr>
                                                    <th colspan ="2" align="center">Edit Your Review</th>
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
                                                    <td colspan ="2" align="center"><button type="submit" name="editRevButton" id="editRevButton">Edit Review</button></td>
                                                </tr>
                                        </table>
                                </div>
                        </form>
                        <br/>';                  
            }
            else
            {
                echo'You do not have permission accessing this page!';
            }
            
            if (isset($_POST['editRevButton'])) {
                $rating = $_POST['rating'];
                $content = $_POST['content'];

                if ($rating != "" && $content != "") 
                {
                    mysql_query("UPDATE nxline_review SET star =".$rating.", content = '".mysql_escape_string($content)."' WHERE review_id =".$row['review_id']);
                    
                    mysql_query("UPDATE nxline_shop SET star =".  getShopRating($row['shop_id'])." WHERE shop_id =".$row['shop_id']); 
                    
                    echo'<script> window.location="review.php?shop_addr_id='.$row['shop_addr_id'].'"; </script> ';                       
                }
                else if ($rating != ""){
                    
                    mysql_query("UPDATE nxline_review SET star =".$rating." WHERE review_id =".$row['review_id']);
                    
                    mysql_query("UPDATE nxline_shop SET star =".  getShopRating($row['shop_id'])." WHERE shop_id =".$row['shop_id']);
                    
                    echo'<script> window.location="review.php?shop_addr_id='.$row['shop_addr_id'].'"; </script> ';               
                }
                else if ($content != "")
                {
                    mysql_query("UPDATE nxline_review SET content= '".mysql_escape_string($content)."' WHERE review_id =".$row['review_id']);
                    
                    print($content);
                    
                    echo'<script> window.location="review.php?shop_addr_id='.$row['shop_addr_id'].'"; </script> ';
                }
                else
                {
                    echo'Please make an edting!! <br/>';
                }
            }
        }
    }
}


require("footer.inc.php");