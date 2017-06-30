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
if($errors==0)
{   $pass=password_hash($pass,PASSWORD_DEFAULT);
	$type="student";
	$sql =$conn->prepare("INSERT INTO users(name,email,username,password,type) VALUES(?,?,?,?,?)");
	$sql->bind_param("sssss",$name,$email,$user,$pass,$type);
	$result=$sql->execute();
    header("location:login.php");
}
}
?>
<!DOCTYPE html>
<head>
<title>Registration Page-Online Notice Board</title>
</head>
<body>
<p>All * fields are mandatory</p>
<p>On successful registration, you will be redirected to the login page.</p>
<form action="<?php echo htmlentities($_SERVER["PHP_SELF"]);?>" method="post">
<label>Name:<input type = "text" name = "personname"/></label><span class="error">* <?php echo $nameErr;?></span><br>
<label>Email:<input type = "text" name = "email"/></label><span class="error">* <?php echo $emailErr;?></span><br>
<label>Username:<input type = "text" name = "username"/></label><span class="error">* <?php echo $userErr;?></span><br>
<label>Password:<input type = "password" name = "password"/></label><span class="error">* <?php echo $passErr;?></span><br>
<input type = "submit" value = "Submit"/><span class="error"><?php echo $submitErr;?></span><br>
<p>Already a member? <a href="login.php">Log In</a></p>
</form>
</body>
</html>