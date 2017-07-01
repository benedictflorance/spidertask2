<?php
ini_set('display_errors', 1); 
ini_set('log_errors',1); 
error_reporting(E_ALL); 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include("config.php");
echo "<head><title>Bulletin Board</title><link href=\"view.css\" type=\"text/css\" rel=\"stylesheet\"/>
<link href=\"https://fonts.googleapis.com/css?family=Open+Sans\" rel=\"stylesheet\">
<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'><head><body><div class=\"outer\">
<div class=\"middle\">";
session_start();
if(isset($_SESSION['username']))
{	echo "<h1>Bulletin Board</h1>";
	echo "<h2>Welcome ".ucwords($_SESSION['usertype'])." {$_SESSION['name']} !</h2><br>";
	if($_SESSION['usertype']=="professor")
	{
		echo "<a id=\"button\" href=\"adminpanel.php\">Go to Admin Panel </a>";
	}
	if($_SESSION['usertype']=="CR")
	{
		echo "<a id=\"button\" href=\"crpanel.php\">Go to CR Panel</a>";
	}
	echo"<a id=\"button\" href=\"logout.php\">Log Out</a><table><tr><th>S. No.</th><th>Subject</th><th>Type</th><th>Content</th><th>Issued on</th>";
	$sql="SELECT * FROM NOTES";
	$result=mysqli_query($conn,$sql);
	$i=1;
	while($row=mysqli_fetch_array($result))
	{
	echo "<tr><td>".$i."</td><td>{$row['subject']}</td><td>".ucwords($row['type'])."</td><td>{$row['content']}</td><td>{$row['date']}</td>";
	$i++;
	}
	echo"</table></div></div></body>";

}
else
	echo "<h1>Access Denied</h2><br><a id=\"button\" href=\"login.php\">Click here to log in</a></div></div></body>";
?>
