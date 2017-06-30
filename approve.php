<?php
ini_set('display_errors', 1); 
ini_set('log_errors',1); 
error_reporting(E_ALL); 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include("config.php");
date_default_timezone_set('Asia/Kolkata');
session_start();
$appsubjectErr=$appsubmitErr=$appdateErr='';
$apperrors=0;
if(isset($_SESSION['usertype'])&&$_SESSION['usertype']=="professor")
{
if(isset($_POST['approve'])||isset($_POST['disapprove'])){
$appsubject = mysqli_real_escape_string($conn,$_POST['appsubject']);
$apptype = mysqli_real_escape_string($conn,$_POST['apptype']);
$appdate =mysqli_real_escape_string($conn,$_POST['appdate']);
$flag=1;
if(empty($appsubject))
   {$appsubjectErr="Subject cannot be empty";
	$apperrors++;}
if(empty($appdate))
   {$appdateErr="Date cannot be empty";
	$apperrors++;}
if($apperrors==0)
{   $content='';
	if(isset($_POST['approve']))
	{
	$date=date("d/m/Y");
	$sql =$conn->prepare("SELECT content FROM pendingnotes WHERE subject=? AND type=? AND date=? LIMIT 1");
	$sql->bind_param("sss",$appsubject,$apptype,$appdate);
	$sql->execute();
	$result=$sql->get_result();
	$row=mysqli_fetch_array($result);	
	if(mysqli_num_rows($result)>0)
	{
	$content=$row['content'];}
	else{$appsubmitErr="Pending note not found";
	$flag=0;
	}
	if($flag)
	{
	$sql =$conn->prepare("INSERT INTO notes(subject,type,content,date) VALUES(?,?,?,?)");
	$sql->bind_param("ssss",$appsubject,$apptype,$content,$date);
	$sql->execute();
	$sql =$conn->prepare("DELETE FROM pendingnotes WHERE subject=? AND type=? AND date=?");
	$sql->bind_param("sss",$appsubject,$apptype,$appdate);
	$sql->execute();
	$appsubmitErr=ucwords($apptype)." approved and added successfully";
	}
	}
	if(isset($_POST['disapprove']))
	{
	$sql =$conn->prepare("DELETE FROM pendingnotes WHERE subject=? AND type=? AND date=?");
	$sql->bind_param("sss",$appsubject,$apptype,$appdate);
	$sql->execute();
	if($sql->affected_rows>0)
		$appsubmitErr=ucwords($apptype)." deleted successfully";
	else
		$appsubmitErr=ucwords($apptype)." does not exist";
	}

}}
echo"<form action=\"";echo htmlentities($_SERVER["PHP_SELF"]);echo "\" method=\"post\">
<a href=\"view.php\">Back to Bulletin Board</a>
<a href=\"adminpanel.php\">Back to Admin Panel</a>
<a href=\"logout.php\">Log Out</a><br>
<p>Approve or Disapprove Notes/Assignment</p>
<label> Subject:* <input type = \"text\" name = \"appsubject\"/></label><span class=\"error\">";echo $appsubjectErr;echo"</span><br>
Type?*
<label><input type=\"radio\" name=\"apptype\" value=\"notes\" checked>Notes</label>
<label><input type=\"radio\" name=\"apptype\" value=\"assignment\">Assignment<br></label>
<label> Date:* <input type = \"text\" name = \"appdate\"/ placeholder=\"dd/mm/yy\"></label><span class=\"error\">";echo $appdateErr;echo"</span><br>
<input type =\"submit\" name=\"approve\" value = \"Approve\"/>
<input type =\"submit\" name=\"disapprove\" value = \"Disapprove\"/><br>";
echo $appsubmitErr;echo "</form>";
}
else
	echo "Access Denied<br><a href=\"login.php\">Click here to log in</a>";
?>