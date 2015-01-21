<?php
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

?>