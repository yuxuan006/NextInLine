<?php
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

$email_message = "
Name: ".$name."
Email: ".$email."
Message: ".$message."
";

mail ("info@nextinlineone.com", "New inquiry", $email_message);

print '<script type="text/javascript">';
print 'alert("Yippe! Your email has been sent!")'; 
print '</script>'; 

header("Location: ./index.php"); 

?>