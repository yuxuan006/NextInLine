<?php

require("header.inc.php");

/*echo '<section id="threeColumnLayout" class="row">
      <div class="center">
        <h1></h1>
        <strong class="subHeading"></strong>
        <div class="threeColumns">
          <div class="eachColumn">
          <div class="imgHolder alignLeft"><img src="img/restaurant21.png" alt="" style="float: left;"></div>
            <h2 align="center">What is included in our package?</h2>
            <div class="imgHolder alignLeft"><img src="img/restaurant21.png" alt="" style="float: left;"></div>
            <br>
            <br>
            <p>1. You can process all your To-Go orders without answering a single phone call.</p>
            <p>2. Take dine-in orders to cut wait time.</p>
            <p>3. Process payments at the table.</p>
            <p>4. Let Guest pay check on their phones.</p>
          </div>
          <div class="eachColumn">
          	<p>Think of a Software that will let you see what your customers want to eat before they get to your restaurant. You can have your customers order before they arrive. It cuts the wait time.</p>
            <hr>
             	<p>Want to learn more? Connect with one of our account managers for help.</p>
            <hr>
            <strong>Get the Best Results You Want.</strong>
            <br>
            <p>Seat More Guest, Faster Turn Out.</p>
            <p>Let your waiters carry Tablets with them, take and send orders to kitchen at Guest’s table without going back and forth.</p>
          </div>
          <div class="eachColumn">
          	<strong>Take orders on tablet</strong>
            <div class="imgHolder alignLeft"><img src="" alt=""></div>
            <p>We will provide customized tablets for the number of waiters you staff per shift. Equipped with our software but customized to fit your restaurant needs. Contact us for more information.</p>
            <strong>OUR PRODUCTS AND SERVICES</strong>
            <p>We created this software with you as a restaurant in mind. We think like you but we also think like your guest. We know what guest want and what they expect from you when they dine at your restaurant. Make them happy by giving them the most efficient service around</p>
            
          </div>
        </div>
      </div>
    </section>'; */


echo '

<hr>
<div id="contentPage" class="home">
  <div class="inner">
    <h2><span>Our package for you</span></h2>
    <div class="section more">
    <img src="img/tablet2.png">
    </div>
    
    <h2><span>Think about this</span></h2>
    <div class="section about">
      
      <p>Think of a software that will let you see what your customers want to eat before they get to your restaurant. You can have your customers order before they arrive. It cuts the wait time.</a></p>
      <br>
      <p class="contactInfo"><i>What to know more? <a href="contact_us.php">Connect with</a> one of our account managers for help.</i></p>
    </div>
    
    <h2><span>More Information</span></h2>
    <div class="section more">
      <div class="text">
        <h3 class="with-icon checkmark">Get the Best Results You Want</h3>
        <p>Seat More Guest, Faster Turn Out.</p>
        <p>Let your waiters carry Tablets with them, take and send orders to kitchen at Guest’s table without going back and forth.</p>
        <a href="#" class="continue"></a> </div>
      <div class="text">
        <h3 class="with-icon checkmark">Take orders on Tablet</h3>
        <p>We will provide customized tablets for the number of waiters you staff per shift. Equipped with our software but customized to fit your restaurant needs.</p>
        <a href="#" class="continue">contact us &raquo;</a></div>
        <div class="text">
        <h3 class="with-icon checkmark">Our Products and Services</h3>
        <p>We created this software with you as a restaurant in mind. We think like you but we also think like your guest. We know what guest want and what they expect from you when they dine at your restaurant. Make them happy by giving them the most efficient service around.</p>
        <a href="#" class="continue"></a> </div>
    </div>
</div>

<div class="section about">
<h2><span>Contact us</span></h2>
<div id="form-main">
  <div id="form-div">
    <form class="form" id="form1" action="restaurant_owner_page.php" method="post" enctype="multipart/form-data">
      
      <p class="name">
        <input name="Name" type="text" class="validate[required,custom[onlyLetter],length[0,100]] feedback-input" placeholder="Name" id="name" />
      </p>
      
      <p class="email">
        <input name="Email" type="text" class="validate[required,custom[email]] feedback-input" id="email" placeholder="Email" />
      </p>
      
      <p class="text">
        <textarea name="Message" class="validate[required,length[6,300]] feedback-input" id="comment" placeholder="Message"></textarea>
      </p>
      
      
      <div class="submit">
        <input type="submit" value="Submit" id="button-blue"/>
        <div class="ease"></div>
      </div>
    </form>
  </div>
 </div>
 </div>
</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br>';

  
                        
require("footer.inc.php");

?>

<?php

if(isset($_POST['button-blue'])){
$name = $_POST['Name'];
$email = $_POST['Email'];
$message = $_POST['Message'];

$email_message = "
Name: ".$name."
Email: ".$email."
Message: ".$message."
";

mail ("info@nextinlineone.com", "New inquiry", $email_message);

print '<script type="text/javascript">';
print 'alert("Yippe! Your email has been sent!")'; 
print '</script>'; 

header("Location: ./restaurant_owner_page.php"); 
}
?>