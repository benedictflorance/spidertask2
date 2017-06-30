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
$subject = trim(htmlspecialchars($_POST['subject']));
$subject = preg_replace('!\s+!', ' ', $subject);
$type = "assignment";
$content =trim(htmlspecialchars($_POST['content']));
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
	$sql =$conn->prepare("INSERT INTO notes(subject,type,content,date) VALUES(?,?,?,?)");
	$sql->bind_param("ssss",$subject,$type,$content,$date);
	$result=$sql->execute();
	$submitErr="Assignment added successfully";
}
}
echo "<!DOCTYPE html>
<head>
<title>
CR Panel
</title>
</head>
<body>
<a href=\"view.php\">View Notes</a>
<a href=\"logout.php\">Log Out</a><br>
<form action=\"";echo htmlentities($_SERVER["PHP_SELF"]);echo "\" method=\"post\">
<p>All fields are mandatory</p>
<p>Add Assignment</p>
<label> Subject:* <input type = \"text\" name = \"subject\"/></label><span class=\"error\">";echo $subjectErr;echo"</span><br>
<label> Content:* <textarea name =\"content\"></textarea></label><span class=\"error\">";echo $notesErr;echo "</span><br>
<input type =\"submit\" name=\"addsubmit\" value = \"Add\"/><span class=\"error\">";echo $submitErr;echo "</span>
</form>
</body>
</html>";
} 
?>