<html>
<head> <title>Grp23 Crowdfunding Login</title> </head>
<body>
<table>
<tr> <td colspan="2" style="background-color:#FFA500;">
<h1> Grp23 Crowdfunding Login</h1>
</td> </tr>

<?php
$dbconn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=adam")
    or die('Could not connect: ' . pg_last_error());
?>

<tr>
<td style="background-color:#eeeeee;">
<form method="post">
        Username: <input type="text" name="Username" id="Username"> <br\>
		Password: <input type="password" name="Password" id="Password"> <br\>
		<input type="submit" name="formSubmit" value="Login" >
</form>
<?php if(isset($_POST['formSubmit'])) 
{
	//$query = 'SELECT * FROM user';
	$query = "SELECT COUNT(*) FROM public.user WHERE username = '".$_POST['Username']."' AND password ='".$_POST['Password']."'";
	//public is the schema name
	//$query = "SELECT COUNT(*) FROM public.user WHERE username = 'adam' AND password = 'adam'";
	$result = pg_query($query) or die('Query failed: ' . pg_last_error());

	//echo "<br\>Username is : ".$_POST['Username']."<br\>";
	//echo "<br\>password is : ".$_POST['Password']."<br\>";
	//echo "<br\>result is : ".$result."<br\>";
	
	//echo "<b>SQL: </b>".$query."<br\><br\>";
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	
    //echo "\t<tr>\n";
    foreach ($line as $col_value) {
        //echo "\t\t<td>$col_value</td>\n";
		if($col_value == 1) {
			header('Location: loginSuccess.php');
		}
		else if ($col_value == 0) {
			//header('Location: loginFailed.php');
			echo "<br\>Login Failed\n<br\>";
		}
    }
    //echo "\t</tr>\n";
  } 
	
	pg_free_result($result);
}
?>
</td> </tr>
<?php
pg_close($dbconn);
?>

</table>

<br\>
<a href="register.php">Register</a>
<br\>

</body>
</html>
