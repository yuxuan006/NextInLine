<!DOCTYPE html>
<html>
<head>
<script LANGUAGE="javascript">
           
            function openwin(){
                window.open("contact_us.php","newwindow","width=500,height=450,top=300,left=500,toolbar=no,scrillbars=no,resizable=no,location=o,status=no")
            }
            
</script>
<link rel="stylesheet" href="css/style.css" type="text/css">        
</head>
<body>
<div id="form-main">
  <div id="form-div">
    <form class="form" id="form1" action="restaurant_owner_contact.php" method="post" enctype="multipart/form-data">
      
      <p class="name">
        <input name="name" type="text" class="validate[required,custom[onlyLetter],length[0,100]] feedback-input" placeholder="Name" id="name" />
      </p>
      
      <p class="email">
        <input name="email" type="text" class="validate[required,custom[email]] feedback-input" id="email" placeholder="Email" />
      </p>
      
      <p class="text">
        <textarea name="message" class="validate[required,length[6,300]] feedback-input" id="comment" placeholder="Message"></textarea>
      </p>
      
      
      <div class="submit">
        <input type="submit" value="Submit" id="button-blue"/>
        <div class="ease"></div>
      </div>
    </form>
  </div>
 </div>
</body>
</html>