<?php
ini_set('display_errors', 1); 
ini_set('log_errors',1); 
error_reporting(E_ALL); 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include("config.php");
date_default_timezone_set('Asia/Kolkata');
session_start();
if(isset($_SESSION['usertype'])&&$_SESSION['usertype']=="CR")
{
$notesErr=$subjectErr=$submitErr='';
$errors=$delerrors=$cherrors=0;
if(isset($_POST['addsubmit'])){
$subject = trim(mysqli_real_escape_string($conn,$_POST['subject']));
$subject = preg_replace('!\s+!', ' ', $subject);
$type = "assignment";
$content =trim(mysqli_real_escape_string($conn,$_POST['content']));
if(empty($subject))
   {$subjectErr="Subject cannot be empty";
	$errors++;}
if(empty($content))
   {$notesErr="Content cannot be empty";
	$errors++;}
if (!preg_match("/^[a-zA-Z ]*$/",$subject)) 
{
	$nameErr="Only letters, numbers and space allowed";
	$errors++;
}
if($errors==0)
{   
	$date=date("d/m/Y");
	$sql =$conn->prepare("INSERT INTO pendingnotes(subject,type,content,date) VALUES(?,?,?,?)");
	$sql->bind_param("ssss",$subject,$type,$content,$date);
	$result=$sql->execute();
	$submitErr="Assignment sent for approval successfully";
}
}
echo "
<!DOCTYPE html>
<head><title>CR PANEL</title><link href=\"adminpanel.css\" type=\"text/css\" rel=\"stylesheet\"/>
<link href=\"https://fonts.googleapis.com/css?family=Open+Sans\" rel=\"stylesheet\">
<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'></head><body><div class=\"outer\">
<div class=\"middle\">
<h1>Class Representative Panel</h1>
<span style=\"color:red\">All * fields are mandatory</span><br><br>
<body>
<a href=\"view.php\" id=\"button\" class=\"green\">Back to Bulletin Board</a>
<a href=\"logout.php\" id=\"button\" class=\"green\">Log Out</a><br>
<form action=\"";echo htmlentities($_SERVER["PHP_SELF"]);echo "\" method=\"post\">
<h2>Add Assignment for approval:</h2>
<label> Subject:<span style=\"color:red\">*</span> <input type = \"text\" name = \"subject\"/></label><span class=\"error\">";echo $subjectErr;echo"</span><br>
<label> Content:<span style=\"color:red\">*</span><textarea name =\"content\"></textarea></label><span class=\"error\">";echo $notesErr;echo "</span><br>
<input id=\"button\" class=\"red\" type =\"submit\" name=\"addsubmit\" value = \"Add\"/><span class=\"error\">";echo $submitErr;echo "</span>
</div>
</div>
</form>
</body>
</html>";
} 
else
	echo "<!DOCTYPE html>
<head><title>CR Panel</title><link href=\"adminpanel.css\" type=\"text/css\" rel=\"stylesheet\"/>
<link href=\"https://fonts.googleapis.com/css?family=Open+Sans\" rel=\"stylesheet\">
<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'></head><body><div class=\"outer\">
<div class=\"middle\"><h1>Access Denied</h2><br><a id=\"button\" class=\"green\" href=\"login.php\">Click here to log in</a></div></div></body></html>";
?>