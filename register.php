<?php
   ini_set('display_errors', 1); 
   ini_set('log_errors',1); 
   error_reporting(E_ALL); 
   mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
 include("config.php");
 $userErr=$passErr=$nameErr=$emailErr=$submitErr="";
 if($_SERVER["REQUEST_METHOD"] == "POST") 
{
$errors=0;
$name = trim(mysqli_real_escape_string($conn,$_POST['personname']));
$email = mysqli_real_escape_string($conn,$_POST['email']);
$user = trim(mysqli_real_escape_string($conn,$_POST['username']));
$pass = mysqli_real_escape_string($conn,$_POST['password']);
$name = preg_replace('!\s+!', ' ', $name);
if(empty($name))
   {$nameErr="Name cannot be empty";
	$errors++;}
if(empty($email))
   	{$emailErr="Email cannot be empty";
	$errors++;}
if(empty($pass))
   {$passErr="Password cannot be empty";
	$errors++;}
if(empty($user))
   	{$userErr="Username cannot be empty";
	$errors++;}
if(!filter_var($email,FILTER_VALIDATE_EMAIL))
	{$emailErr="Invalid Email Id";
	$errors++;}
if (!preg_match("/^[a-zA-Z ]*$/",$name)) 
{
	$nameErr="Only letters and space allowed";
	$errors++;
}
if (!preg_match("/^[a-z0-9_.]*$/",$user)) 
{
	$userErr="Only lower alphanumeric, _ and . allowed";
	$errors++;
}
	$query="SELECT * FROM users WHERE username='".$user."'";
	$qresult=mysqli_query($conn,$query);
	if($qresult->num_rows>0)
	{	$userErr="Username already exists";
	$errors++;
	}
   if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
     $secret = '6Ld4dScUAAAAALM1JwK-cyterioH49a4AxlpywuI';
     $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
     $responseData = json_decode($verifyResponse);
     if($responseData->success){
if($errors==0)
{   $pass=password_hash($pass,PASSWORD_DEFAULT);
	$type="student";
	$moderated="no";
	$sql =$conn->prepare("INSERT INTO users(name,email,username,password,type,moderated) VALUES(?,?,?,?,?,?)");
	$sql->bind_param("ssssss",$name,$email,$user,$pass,$type,$moderated);
	$result=$sql->execute();
    header("location:login.php");
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
<title>Registration Page-Online Notice Board</title>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
<p>All * fields are mandatory</p>
<p>On successful registration, you will be redirected to the login page.</p>
<form action="<?php echo htmlentities($_SERVER["PHP_SELF"]);?>" method="post">
<label>Name:<input type = "text" name = "personname"/></label><span class="error">* <?php echo $nameErr;?></span><br>
<label>Email:<input type = "text" name = "email"/></label><span class="error">* <?php echo $emailErr;?></span><br>
<label>Username:<input type = "text" name = "username"/></label><span class="error">* <?php echo $userErr;?></span><br>
<label>Password:<input type = "password" name = "password"/></label><span class="error">* <?php echo $passErr;?></span><br>
<div class="g-recaptcha" data-sitekey="6Ld4dScUAAAAAMNd65qyE8smg_uZqbS7vZMGGTvr"></div>
<input type = "submit" value = "Submit"/><span class="error"><?php echo $submitErr;?></span><br>
<p>Already a member? <a href="login.php">Log In</a></p>
</form>
</body>
</html>