<?php
ini_set('display_errors', 1); 
ini_set('log_errors',1); 
error_reporting(E_ALL); 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include("config.php");
date_default_timezone_set('Asia/Kolkata');
session_start();
if(isset($_SESSION['usertype'])&&$_SESSION['usertype']=="professor")
{
$notesErr=$subjectErr=$submitErr=$deltypeErr=$delsubmitErr=$delsubjectErr=$deldateErr=$chuserErr=$chsubmitErr='';
$errors=$delerrors=$cherrors=0;
if(isset($_POST['addsubmit'])){
$subject = trim(htmlspecialchars($_POST['subject']));
$subject = preg_replace('!\s+!', ' ', $subject);
$type = htmlspecialchars($_POST['type']);
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
	$submitErr=ucwords($type)." added successfully";
}
}
if(isset($_POST['delsubmit'])){
$delsubject = htmlspecialchars($_POST['delsubject']);
$deltype = htmlspecialchars($_POST['deltype']);
$deldate =htmlspecialchars($_POST['deldate']);
if(empty($delsubject))
   {$delsubjectErr="Subject cannot be empty";
	$delerrors++;}
if(empty($deldate))
   {$deldateErr="Date cannot be empty";
	$delerrors++;}
if($delerrors==0)
{   
	$sql =$conn->prepare("DELETE FROM notes WHERE subject=? AND type=? AND date=?");
	$sql->bind_param("sss",$delsubject,$deltype,$deldate);
	$sql->execute();
	if($sql->affected_rows>0)
		$delsubmitErr="Note deleted successfully";
	else
		$delsubmitErr="Note does not exist";

}
}
if(isset($_POST['chsubmit'])){
$chuser = htmlspecialchars($_POST['chuser']);
$chtype = htmlspecialchars($_POST['chtype']);
if(empty($chuser))
   {$chuserErr="Username cannot be empty";
	$cherrors++;}
if($cherrors==0)
{   
	$sql =$conn->prepare("UPDATE users SET type=? WHERE username=?");
	$sql->bind_param("ss",$chtype,$chuser);
	$sql->execute();
	if($sql->affected_rows>0)
		$chsubmitErr="Changed access type of {$chuser} to {$chtype}.";
	else
		$chsubmitErr="Username not found";

}
}
echo "<!DOCTYPE html>
<head>
<title>
Admin Panel
</title>
</head>
<body>
<a href=\"view.php\">View Notes</a>
<a href=\"logout.php\">Log Out</a><br>
<form action=\"";echo htmlentities($_SERVER["PHP_SELF"]);echo "\" method=\"post\">
<p>All fields are mandatory</p>
<p>Add Notes/Assignment</p>
<label> Subject:* <input type = \"text\" name = \"subject\"/></label><span class=\"error\">";echo $subjectErr;echo"</span><br>
Type?*
<label><input type=\"radio\" name=\"type\" value=\"notes\" checked>Notes</label>
<label><input type=\"radio\" name=\"type\" value=\"assignment\">Assignment<br></label>
<label> Content:* <textarea name =\"content\"></textarea></label><span class=\"error\">";echo $notesErr;echo "</span><br>
<input type =\"submit\" name=\"addsubmit\" value = \"Add\"/><span class=\"error\">";echo $submitErr;echo "</span>
</form>
<form action=\"";echo htmlentities($_SERVER["PHP_SELF"]);echo "\" method=\"post\">
<p>Delete Notes/Assignment</p>
<label> Subject:* <input type = \"text\" name = \"delsubject\"/></label><span class=\"error\">";echo $delsubjectErr;echo"</span><br>
Type?*
<label><input type=\"radio\" name=\"deltype\" value=\"notes\" checked>Notes</label>
<label><input type=\"radio\" name=\"deltype\" value=\"assignment\">Assignment<br></label>
<label> Date:* <input type = \"text\" name = \"deldate\"/ placeholder=\"dd/mm/yy\"></label><span class=\"error\">";echo $deldateErr;echo"</span><br>
<input type =\"submit\" name=\"delsubmit\" value = \"Delete\"/><span class=\"error\">";echo $delsubmitErr;echo "</span>
</form>
<form action=\"";echo htmlentities($_SERVER["PHP_SELF"]);echo "\" method=\"post\">
<p>Change permission level of an user:</p>
<label>Username of the user:*<input type = \"text\" name = \"chuser\" </label><span class=\"error\">";echo $chuserErr;echo"</span><br>Type?*
<label><input type=\"radio\" name=\"chtype\" value=\"student\" checked>Student</label>
<label><input type=\"radio\" name=\"chtype\" value=\"professor\">Professor</label>
<label><input type=\"radio\" name=\"chtype\" value=\"CR\">Class Representative<br></label>
<input type =\"submit\" name=\"chsubmit\" value = \"Change\"/><span class=\"error\">";echo $chsubmitErr;echo "</span>
</form>
</body>
</html>";
}
else 
	echo "Access Denied<br><a href=\"login.php\">Click here to log in</a>";
?>
