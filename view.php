<?php
ini_set('display_errors', 1); 
ini_set('log_errors',1); 
error_reporting(E_ALL); 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include("config.php");
session_start();
if(isset($_SESSION['username']))
{	echo "<head><title>Notes and Assignments</title><head>";
	echo "Welcome ".ucwords($_SESSION['usertype'])." {$_SESSION['name']} !<br>";
	if($_SESSION['usertype']=="professor")
	{
		echo "<a href=\"adminpanel.php\">Go to Admin Panel </a>";
	}
	if($_SESSION['usertype']=="CR")
	{
		echo "<a href=\"crpanel.php\">Go to CR Panel</a>";
	}
	echo"<br><a href=\"logout.php\">Log Out</a><table><tr><th>S. No.</th><th>Subject</th><th>Type</th><th>Content</th><th>Issued on</th>";
	$sql="SELECT * FROM NOTES";
	$result=mysqli_query($conn,$sql);
	$i=1;
	while($row=mysqli_fetch_array($result))
	{
	echo "<tr><td>".$i."</td><td>{$row['subject']}</td><td>".ucwords($row['type'])."</td><td>{$row['content']}</td><td>{$row['date']}</td>";
	$i++;
	}
	echo"</table>";

}
else
	echo "Access Denied<br><a href=\"login.php\">Click here to log in</a>";
?>
