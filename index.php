<?php
require("header.inc.php");

echo'<div id="searchShop">
        <form id="searchForm" method="post" action="./index.php">
            <table class = "table" id = "searchTable" style="color:#3B3738">
                <tr>
                    <th colspan ="7" align="center" style="color:#3B3738">Order Ahead or Place To Go Order<br><br></th>
                </tr>
              
                <tr>
                    <!--td>Name: </td-->
                    <td><input type="text" name="shopName" id="shopName" placeholder="Restaurant/Bar&#39;s name"/ style="background-color:#f2f2f2; height: 30px; width:170px; text-align=center; border: solid 5px #c9c9c9;"></td>
                    <!--td>Zip Code: </td-->
                    <td><input type="text" name="zip" id="zip" placeholder="E.g: 44601" style="background-color:#f2f2f2; height: 30px; width:170px; text-align=center; border: solid 5px #c9c9c9;"/></td>
                    <!--td>Category: </td-->
                    <td>
                    <div class="categorySelected">
                    <select name="category" id ="category">
                    <option value="">--Category--</option>';
                        $categoryList = mysql_query("SELECT * FROM nxline_shop_category ORDER BY cat_name asc");
                        while ($category = mysql_fetch_array($categoryList)) {
                            echo'<option value="' . $category['cat_name'] . '">' . $category['cat_name'] . '</option>';
                        }
                    echo'</select>
                    </div>
                    </td>
                    <td><button type="submit" name="searchButton" id="searchButton">Search</button></td>
                </tr>
            </table>
        </form>
    </div>';

?>

<div class="slideshow">
	<div class="slides_container">
		<div class="slide item-2">
			<div class="slide-context">
				<h1>Are you Hungry?</h1>
				<div class="clear"></div>
				<h2>Search restaurants, view menu and order!</h2>
			</div>
		</div>
		<div class="slide item-3">
			<div class="slide-context">
				<h1>You can ahead</h1>
				<div class="clear"></div>
				<h2>order your meal before you arrive!</h2>
			</div>
		</div>
		<div class="slide item-4">
			<div class="slide-context">
				<h1 class="">You can still order To-go</h1>
				<div class="clear"></div>
				<h2>to-go orders on our Phone, no calls!</h2>
			</div>
		</div>
	</div>
</div><!--end slideshow-->

<?php
                    
print("<br/>");                    
                    
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
                    echo'<div id="result">
                            <form id="resultForm" method="post" action="./login.php">
                            <a href="shop.php?shop_id='.$nameResult['shop_id'].'">
                                <table id = "resultTable">
                                    <tr>
                                        <th rowspan="3"><img src="'.$nameResult['shop_pic'].'" alt="'. $nameResult['shop_name'].'"></th>
                                        <td><b>' . $nameResult['shop_name'] . '</b></td>
                                    </tr>
                                    <tr>
                                        <td>' . $nameResult['star'] . '</td>
                                    </tr>
                                        <td>' . $nameResult['shop_desc'] . '</td>
                                </table>
                                </a>
                            </form>
                        </div>
                        <br>';
                }
            }
        }
    } else if ($name != "") {
        if ($zip != "") {
            while ($zipResult = mysql_fetch_array($queryZip)) {
                $queryName = mysql_query("SELECT * FROM nxline_shop WHERE shop_name LIKE '%" . mysql_real_escape_string($name) . "%' AND shop_id IN (" . (int) mysql_real_escape_string($zipResult['shop_id']) . ") ORDER BY shop_name asc");

                if (mysql_num_rows($queryName)) {
                    $nameResult = mysql_fetch_array($queryName);

                    echo'<div id="result">
                            <form id="resultForm" method="post" action="./login.php">
                            <a href="shop.php?shop_id='.$nameResult['shop_id'].'">
                                <table id = "resultTable">
                                    <tr>
                                        <th rowspan="3"><img src="'.$nameResult['shop_pic'].'" alt="'. $nameResult['shop_name'].'"></th>
                                        <td><b>' . $nameResult['shop_name'] . '</b></td>
                                    </tr>
                                    <tr>
                                        <td>' . $nameResult['star'] . '</td>
                                    </tr>
                                        <td>' . $nameResult['shop_desc'] . '</td>
                                </table>
                                </a>
                            </form>
                        </div>
                        <br>';
                }
            }
        } else {
            $queryName = mysql_query("SELECT * FROM nxline_shop WHERE shop_name LIKE '%" . mysql_real_escape_string($name) . "%'");

            if (mysql_num_rows($queryName)) {
                $nameResult = mysql_fetch_array($queryName);

                    echo'<div id="result">
                            <form id="resultForm" method="post" action="./login.php">
                            <a href="shop.php?shop_id='.$nameResult['shop_id'].'">
                                <table id = "resultTable">
                                    <tr>
                                        <th rowspan="3"><img src="'.$nameResult['shop_pic'].'" alt="'. $nameResult['shop_name'].'"></th>
                                        <td><b>' . $nameResult['shop_name'] . '</b></td>
                                    </tr>
                                    <tr>
                                        <td>' . $nameResult['star'] . '</td>
                                    </tr>
                                        <td>' . $nameResult['shop_desc'] . '</td>
                                </table>
                                </a>
                            </form>
                        </div>
                        <br>';
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
                    echo'<div id="result">
                            <form id="resultForm" method="post" action="./login.php">
                            <a href="shop.php?shop_id='.$nameResult['shop_id'].'">
                                <table id = "resultTable">
                                    <tr>
                                        <th rowspan="3"><img src="'.$nameResult['shop_pic'].'" alt="'. $nameResult['shop_name'].'"></th>
                                        <td><b>' . $nameResult['shop_name'] . '</b></td>
                                    </tr>
                                    <tr>
                                        <td>' . $nameResult['star'] . '</td>
                                    </tr>
                                        <td>' . $nameResult['shop_desc'] . '</td>
                                </table>
                                </a>
                            </form>
                        </div>
                        <br>';
                    }
                }
            }
        } else {
            while ($zipResult = mysql_fetch_array($queryZip)) {
                $queryName = mysql_query("SELECT * FROM nxline_shop WHERE shop_id IN (" . (int) mysql_real_escape_string($zipResult['shop_id']) . ") ORDER BY shop_name asc");

                if (mysql_num_rows($queryName)) {
                    $nameResult = mysql_fetch_array($queryName);

                    echo'<div id="result">
                            <form id="resultForm" method="post" action="./login.php">
                            <a href="shop.php?shop_id='.$nameResult['shop_id'].'">
                                <table id = "resultTable">
                                    <tr>
                                        <th rowspan="3"><img src="'.$nameResult['shop_pic'].'" alt="'. $nameResult['shop_name'].'"></th>
                                        <td><b>' . $nameResult['shop_name'] . '</b></td>
                                    </tr>
                                    <tr>
                                        <td>' . $nameResult['star'] . '</td>
                                    </tr>
                                        <td>' . $nameResult['shop_desc'] . '</td>
                                </table>
                                </a>
                            </form>
                        </div>
                        <br>';
                }
            }
        }
    } else if ($cate != "") {
        $queryName = mysql_query("SELECT * FROM nxline_shop WHERE cat_id = " . (int) $cateResult['cat_id'] . " ORDER BY shop_name asc");

        if (mysql_num_rows($queryName)) {
            while ($nameResult = mysql_fetch_array($queryName)) {
                    echo'<div id="result">
                            <form id="resultForm" method="post" action="./login.php">
                            <a href="shop.php?shop_id='.$nameResult['shop_id'].'">
                                <table id = "resultTable">
                                    <tr>
                                        <th rowspan="3"><img src="'.$nameResult['shop_pic'].'" alt="'. $nameResult['shop_name'].'"></th>
                                        <td><b>' . $nameResult['shop_name'] . '</b></td>
                                    </tr>
                                    <tr>
                                        <td>' . $nameResult['star'] . '</td>
                                    </tr>
                                        <td>' . $nameResult['shop_desc'] . '</td>
                                </table>
                                </a>
                            </form>
                        </div>
                        <br>';
            }
        } else {
            print("No resutl for " . $cate);
        }
    }
}

//$queryName = mysql_query("SELECT * FROM nxline_shop ORDER BY star desc");
//
//echo'<div id="shopSlide">
//        <form id="slideForm" method="post" action="./index.php">
//            Top Restaurants/Bars
//            <br/><br/>
//            <ul class="jcarousel-skin-tango" id="mycarousel" style="width: 800px; left: 0px;">';
//            
//    while ($nameResult = mysql_fetch_array($queryName))
//    {
//        echo'<li><a href="shop.php?shop_id='.$nameResult['shop_id'].'">
//            <table id="shopSlideTable">
//                <tr>
//                    <td><img src="'.$nameResult['shop_pic'].'" alt="'. $nameResult['shop_name'].'"><td>
//                </tr>
//                 <tr>
//                    <td><b>' . $nameResult['shop_name'] . '</b><td>
//                </tr>
//                <tr>
//                    <td>' . $nameResult['star'] . '<td>
//                </tr>                
//            </table>
//            </a></li>';
//    }
//        echo '</form>
//            </div>
//            <br class="clear" />';
//        
//echo'<div id="cateShop">
//        <form id="cateShopForm" method="post" action="./index.php">
//            Restaurant/Bar List<br><br>
//            
//            <table id ="cateTable">
//                <tr>
//                    <th align="center">Category</th>';
//           echo'</tr>
//                <tr>
//                    <td>';
//                        $categoryList = mysql_query("SELECT * FROM nxline_shop_category ORDER BY cat_name asc");
//                        while ($category = mysql_fetch_array($categoryList)) {
//                            echo'<input id="category" name="category" type="radio" value="'. $category['cat_name'].'">' . $category['cat_name'].'<br>';
//                        }                        
//                echo'</td>
//                </tr>
//                <tr>
//                    <td colspan="2" align="center"><button type="submit" name="submit" id="submit">Submit</button></td>
//                </tr>
//            </table>
//      
//            <table id="shopTable">
//		<tr>  
//		    <th align="center">';
//                        if(isset($_POST['submit']))
//                        {
//                            $categoryRadioButton = $_POST['category'];
//                            
//                            echo''.$categoryRadioButton.'';
//                        }
//                        else
//                        {                       
//                            echo'American';                            
//                        }
//               echo'</th>
//                </tr>
//                <tr>
//                    <td align="left">';
//                        if(isset($_POST['submit']))
//                        {
//                            $categoryRadioButton = $_POST['category'];
//                            
//                            $queryCat = mysql_query("SELECT * FROM nxline_shop_category WHERE cat_name = '" . mysql_real_escape_string($categoryRadioButton) . "'");
//
//                            $cateResult = mysql_fetch_array($queryCat);
//                            
//                            $queryName = mysql_query("SELECT * FROM nxline_shop WHERE cat_id = ". (int) $cateResult['cat_id']." ORDER BY shop_name");
//                            
//                                    if (mysql_num_rows($queryName)) {
//                                    while ($nameResult = mysql_fetch_array($queryName)) {
//                                    echo'
//                                        <a href="shop.php?shop_id=' . $nameResult['shop_id'] . '">
//                                            <table id = "resultCatTable">
//                                                <tr>
//                                                    <th rowspan="3"><img src="' . $nameResult['shop_pic'] . '" alt="' . $nameResult['shop_name'] . '"></th>
//                                                    <td><b>' . $nameResult['shop_name'] . '</b></td>
//                                                </tr>
//                                                <tr>
//                                                    <td>' . $nameResult['star'] . '</td>
//                                                </tr>
//                                            </table>
//                                        </a>
//                                    <br>';
//                                }
//                            }
//                        }
//                        else
//                        {
//                            $queryName = mysql_query("SELECT * FROM nxline_shop WHERE cat_id = 1 ORDER BY shop_name");
//                            
//                                    if (mysql_num_rows($queryName)) {
//                                    while ($nameResult = mysql_fetch_array($queryName)) {
//                                    echo'
//                                        <a href="shop.php?shop_id=' . $nameResult['shop_id'] . '">
//                                            <table id = "resultCatTable">
//                                                <tr>
//                                                    <th rowspan="3"><img src="' . $nameResult['shop_pic'] . '" alt="' . $nameResult['shop_name'] . '"></th>
//                                                    <td><b>' . $nameResult['shop_name'] . '</b></td>
//                                                </tr>
//                                                <tr>
//                                                    <td>' . $nameResult['star'] . '</td>
//                                                </tr>
//                                            </table>
//                                        </a>
//                                    <br>'; 
//                                }
//                            }
//                        } 
//               echo'</td>
//		</tr>
//            </table>
//            
//        </form>
//    </div>';

/*echo' <p align="center">Featured Restaurants</p>
 <div id="w" class="clearfix">
    <ul id="sidemenu">
                    <li><a href="#American-content" class="open">American</a></li>
                    <li><a href="#Asian-content">Asian</a></li>
                    <li><a href="#Bakery-content">Bakery</a></li>
                    <li><a href="#Bar-content">Bar</a></li>
                    <li><a href="#Barbecue-content">Barbecue</a></li>
                    <li><a href="#Buffet-content">Buffet</a></li>
                    <li><a href="#Cafe-content">Cafe</a></li>
                    <li><a href="#Mexican-content">Mexican</a></li>
    </ul>
    
    <div id="tabcontent">
        <div id="American-content" class="contentblock">
             
          <div class="media-content">
                        <ul class="list">
                            <li class="media-block">
                            <div class="media-avatar">
                            <div class="photo-box-list1"><a href="shop.php?shop_id=6">
                                <img alt="McDonalds" class="photo-box-img" height="60" src="img/upload/Mcdonalds_logo.png" width="60"></a>
                            </div>
                            </div>
                            <div class="media-story">
                            <div class="media-title">
                                <span class="indexed-biz-name">1.     <a class="biz-name" href="shop.php?shop_id=6" data-hovercard-id="ODF_5JoYt1-cx-PNQtgXaA">McDonalds</a></span>
                            </div>
                            <div>
                            <div class="media-story">
                            <div class="ie-tablecell-hack">
                                Since 1955, we&#39ve been proud to serve the world some of its favorite food.
                            </div>
                            </div>
                            </div>
                            </div>
                            </li>
                            
                            <li class="media-block">
                            <div class="media-avatar-list2">
                            <div class="photo-box-list2"><a href="shop.php?shop_id=5">
                                <img alt="Starbucks" class="photo-box-img" height="60" src="img/upload/photo.jpg" width="60"></a>
                            </div>
                            </div>
                            <div class="media-story-list">
                            <div class="media-title-list2">
                                <span class="indexed-biz-name">2.     <a class="biz-name" href="shop.php?shop_id=5" data-hovercard-id="ODF_5JoYt1-cx-PNQtgXaA">Starbucks</a></span>
                                <a href="http://nextinlineone.com/review.php?shop_addr_id=6">Reviews</a>
                            </div>
                            <div>
                            <div class="media-story-list">
                            <div class="ie-tablecell-hack-list2">
                                Our coffeehouses have become a beacon for coffee lovers everywhere.
                            </div>
                            </div>
                            </div>
                            </div>
                            </li>
                            
                            <li class="media-block">
                            <div class="media-avatar-list3">
                            <div class="photo-box-list3"><a href="shop.php?shop_id=1">
                                <img alt="Texas RoadHouse" class="photo-box-img" height="60" src="img/texas-roadhouse1.jpg" width="60"></a>
                            </div>
                            </div>
                            <div class="media-story-list">
                            <div class="media-title-list3">
                                <span class="indexed-biz-name">3.     <a class="biz-name" href="shop.php?shop_id=1" data-hovercard-id="ODF_5JoYt1-cx-PNQtgXaA">Texas RoadHouse</a></span>
                            </div>
                            <div>
                            <div class="media-story-list">
                            <div class="ie-tablecell-hack-list3">
                                Legendary Food, Legendary Service, and Legendary Fun!
                            </div>
                            </div>
                            </div>
                            </div>
                            </li>
                            
                            <li class="media-block">
                            <div class="media-avatar-list4">
                            <div class="photo-box-list4"><a href="shop.php?shop_id=9">
                                <img alt="Starbucks" class="photo-box-img" height="60" src="img/upload/photo.jpg" width="60"></a>
                            </div>
                            </div>
                            <div class="media-story-list">
                            <div class="media-title-list4">
                                <span class="indexed-biz-name">4.     <a class="biz-name" href="shop.php?shop_id=9" data-hovercard-id="ODF_5JoYt1-cx-PNQtgXaA">Starbucks</a></span>
                            </div>
                            <div>
                            <div class="media-story-list">
                            <div class="ie-tablecell-hack-list4">
                                Our coffeehouses have become a beacon for coffee lovers everywhere.
                            </div>
                            </div>
                            </div>
                            </div>
                            </li>
                         </ur>   
                    </div>
        </div>
        
        
        <div id="Asian-content" class="contentblock hidden">
                    
                    <ul class="list">
                            <li class="media-block">
                            <div class="media-avatar">
                            <div class="photo-box-list1"><a href="shop.php?shop_id=2">
                                <img alt="China Buffet" class="photo-box-img" height="60" src="img/chinabuffet.png" width="60"></a>
                            </div>
                            </div>
                            <div class="media-story">
                            <div class="media-title">
                                <span class="indexed-biz-name">1.     <a class="biz-name" href="/shop.php?shop_id=2" data-hovercard-id="ODF_5JoYt1-cx-PNQtgXaA">China Buffet</a></span>
                            </div>
                            <div>
                            <div class="media-story">
                            <div class="ie-tablecell-hack">
                                All you can eat Chinese Buffet & Mongolian Grill. 
                            </div>
                            </div>
                            </div>
                            </div>
                            </li>
                            
                            <li class="media-block">
                            <div class="media-avatar-list2">
                            <div class="photo-box-list2"><a href="shop.php?shop_id=3">
                                <img alt="Pho Number 1" class="photo-box-img" height="60" src="img/Pho01.jpg" width="60"></a>
                            </div>
                            </div>
                            <div class="media-story-list">
                            <div class="media-title-list2">
                                <span class="indexed-biz-name">2.     <a class="biz-name" href="shop.php?shop_id=3" data-hovercard-id="ODF_5JoYt1-cx-PNQtgXaA">Pho Number 1</a></span>
                            </div>
                            <div>
                            <div class="media-story-list">
                            <div class="ie-tablecell-hack-list2">
                                Vietnamese noodle and rice!
                            </div>
                            </div>
                            </div>
                            </div>
                            </li>
        </div>
        
        <div id="Bakery-content" class="contentblock hidden">
                    
        </div>
        
        <div id="Bar-content" class="contentblock hidden">
             
          
        </div>
        
        
        <div id="Barbecue-content" class="contentblock hidden">
                    
        </div>
        
        <div id="Buffet-content" class="contentblock hidden">
                    
        </div>
        
        <div id="Cafe-content" class="contentblock hidden">
                    
        </div>
        <div id="Mexican-content" class="contentblock hidden">
                    
        </div>
        </div><!-- @end #contact-content -->
    </div><!-- @end #content -->
  </div>';*/

//echo'<div id="advertisement">
  //      <form id="advertisement" method="post" action="./index.php">
    //        Advertisement picture
      //  </form>
    //</div>';

//echo'<div id="appDownload">
  //      <form id="appDownload" method="post" action="./index.php">
    //        Applications for Download <br/><br/>
            
      //      <img src="./img/upload/google-play.jpg" alt="google_play" width="200px" height="80px">
        //    <img src="./img/upload/App_Store_Badge_EN.png" alt="iOS_store" width="200px" height="80px">

//        </form>
  //  </div>';



echo'<div id="contentGuest" class="columns portfolio">
  <div class="inner cf">
    <div class="mainPage">
      <div class="works-list">
        <div class="work cf">
          <div class="description text">
            <h2><a href="restaurant_owner_page.php" class="links">Restaurant Owner</a></h2>
            
            <ul>
            	<li><img src="img/auricular14.png" ></li>
            	<li>You can process all your To-Go orders without answering a single phone call.</li>
            	<li><img src="img/woman81.png" height="45px" width="45px"></li>
            	<li>Seat more guest while cutting the wait time. </li>
            	<li><img src="img/task.png" ></li>
            	<li>Guest can add their name to your list including their meal before they arrive.</li>
            	<li><img src="img/credit56.png" ></li>
            	<li>Process payments at the table. Or let guest pay check on their phones.</li>
            <ul>
            <a href="restaurant_owner_page.php" class="links">Learn more &raquo;</a> 
          </div>
          <div class="gallery">
            <div class="gallery-inner">
              <div class="photos"> <img src="img/tablet.png"> </div>
            </div>
          </div>
         
        </div>
  	
  	<br>
      <hr width="100%"/>	
      <br>
        <div class="work cf">
          <div class="description text">
            <h2><a href="restaurant_guest_page.php" class="links">Restaurant Guest</a></h2>
            <ul>
              <li><img src="img/map33.png"></li>
              <li>Search for restaurants near you and view menu.</li>
              <li><img src="img/smartphone.png" height="45px" width="40px"></li>
              <li>Order Ahead or order To-go without making a phone call.</li>
              <li><img src="img/credit73.png" ></li>
              <li>Don&#039t wait for your check, make a payment on your phone</li>
              <li><img src="img/instagram3.png" ></li>
              <li>Share pics of your meals and experience and review the restaurant.</li>
            </ul>
            <a href="restaurant_guest_page.php" class="links">Click here to read more about what our APP does &raquo;</a> 
          </div>
          <div class="gallery">
            <div class="gallery-inner">
              <div class="photos"> <img src="img/APP.png" > <img src="img/APP1.png" width="460" height="440" alt=""> <img src="img/APP1.png"  width="460" height="440" alt=""> 
              </div>
            </div>
          </div>
          </div>
        </div>
      </div>
      </div>
     </div>';

require("footer.inc.php");
?>