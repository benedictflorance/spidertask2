<?php
   ini_set('display_errors', 1); 
   ini_set('log_errors',1); 
   error_reporting(E_ALL); 
   mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
   include("config.php");
   session_start();
   $userErr=$passErr=$submitErr="";
   if($_SERVER["REQUEST_METHOD"] == "POST") {
     $flag=1;
    $user = mysqli_real_escape_string($conn,$_POST['username']);
    $pass = mysqli_real_escape_string($conn,$_POST['password']); 
   if(empty($pass))
   {$passErr="Password cannot be empty";
  $flag=0;}
   if(empty($user))
    {$userErr="Username cannot be empty";
  $flag=0;}
   if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
     $secret = '6Ld4dScUAAAAALM1JwK-cyterioH49a4AxlpywuI';
     $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
     $responseData = json_decode($verifyResponse);
     if($responseData->success){
	if($flag)
	{
	$sql =$conn->prepare("SELECT name,password,type,moderated FROM users WHERE username =?");
  $sql->bind_param("s",$user);
	$sql->execute();
  $result=$sql->get_result();
	$row=mysqli_fetch_array($result);
  if(password_verify($pass,$row['password'])&&mysqli_num_rows($result)==1) 
  {
  $_SESSION['username']=$user;
  $_SESSION['usertype']=$row['type'];
  $_SESSION['name']=$row['name'];
  $_SESSION['moderated']=$row['moderated'];
  header('location:view.php');}
  else 
    $submitErr="Your login credentials are incorrect";
	}
}
else
  $submitErr="Robot verification failed. Please try again";
}
else
  $submitErr="Please click on the Recaptcha box";
}
?>
<!DOCTYPE html>
<head>
<title>Login Page-Online Notice Board</title>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
<p>All * fields are mandatory</p>
<form action="<?php echo htmlentities($_SERVER["PHP_SELF"]);?>" method="post">
<label>Username:<input type = "text" name = "username"/></label><span class="error">* <?php echo $userErr;?></span><br>
<label>Password:<input type = "password" name = "password"/></label><span class="error">* <?php echo $passErr;?></span><br>
<div class="g-recaptcha" data-sitekey="6Ld4dScUAAAAAMNd65qyE8smg_uZqbS7vZMGGTvr"></div>
<input type = "submit" value = "Submit"/><br><span id="error"><?php echo $submitErr;?></span>
<p>Not yet a member? <a href="register.php">Sign Up</a></p>
</form>
</body>
</html>