<?php
  include_once 'dbconnect.php';
  $error = false;
 
  if (isset($_POST['submit']) ) {
  
    // clean user inputs to prevent sql injections
    $name = trim($_POST['userFullName']);
    $name = strip_tags($name);
    $name = htmlspecialchars($name);
    $userName = trim($_POST['userName']);
    $userName = strip_tags($userName);
    $userName = htmlspecialchars($userName);
  
    $email = trim($_POST['email']);
    $email = strip_tags($email);
    $email = htmlspecialchars($email);
  
    $password = trim($_POST['password']);
    $password = strip_tags($password);
    $password = htmlspecialchars($password);
  
    // basic name validation
    if (empty($name)) {
      $error = true;
      $nameError = "Please enter your full name.";
    } 
    else if (strlen($name) < 3) {
      $error = true;
      $nameError = "Name must have at least 3 characters.";
    } 
    else if (!preg_match("/^[a-zA-Z ]+$/",$name)) {
      $error = true;
      $nameError = "Name must contain alphabets and space.";
    }
  
    // basic username validation
    if (empty($userName)) {
      $error = true;
      $userNameError = "Please enter a valid user name .";
    } 
    else if (strlen($userName) < 6) {
      $error = true;
      $userNameError = "Name must have at least 6 characters.";
    } 
    else if (!preg_match("/[a-zA-Z]/", $userName)) {
      $error = true;
      $userNameError = "Name must contain alphabets.";
    }
    else {
      // check username exist or not
       $query = "SELECT userID FROM users WHERE userEmail='$userName'";
       $result = pg_query($query);
       $count = pg_num_rows($result);
      
       if($count!=0){
         $error = true;
         $emailError = "Username is already in use.";
       }   
       
    }
  
    //basic email validation
    if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
       $error = true;
       $emailError = "Please enter your email address.";
    } 
    else {
       // check email exist or not
       $query = "SELECT userEmail FROM users WHERE userEmail='$email'";
       $result = pg_query($query);
       $count = pg_num_rows($result);
       if($count!=0){
          $error = true;
          $emailError = "Email is already in use.";
       }
    }
    
    // password validation
    if (empty($password)){
       $error = true;
       $passwordError = "Please enter password.";
    } else if(strlen($password) < 6) {
       $error = true;
       $passwordError = "Password must have at least 6 characters.";
    }
  
    // password encrypt using SHA256();
    $password = hash('sha256', $password);
    // if there's no error, continue to signup
    if( !$error ) {
       
       $query = "INSERT INTO users VALUES('$email', '$userName','$name','$password','FALSE')";
       $res = pg_query($query) or die('Query failed: ' . pg_last_error());
    
       if ($res) {
          unset($name);
          unset($email); 
          unset($userName);
          unset($password);
          header("Location: thank-you-registration.html");
       } 
       else {
          $errMSG = "Something went wrong, try again."; 
       } 
    }
  }
?>

<!DOCTYPE HTML>  
<html>
<body>  

<h2>Sign Up </h2>

<form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
	Name: <input type="text" name="userFullName" value="<?php echo $name;?>"><span style="color:red";><?php echo $nameError;?></span>	
	<br>
	Username: <input type="text" name="userName" value="<?php echo $userName;?>"><span style="color:red";><?php echo $userNameError;?></span>
	<br>
	Email: <input type="text" name="email" value="<?php echo $email;?>"><span style="color:red";><?php echo $emailError;?></span>
	<br>
	Password: <input type="password" name="password" value="<?php echo $password;?>"><span style="color:red";><?php echo $passwordError;?></span>
	<br>
  <br>
  <input type="submit" name="submit" value="Submit"><span style="color:red";><?php echo $errMSG; ?></span>
  <br> 
</form>
</body>
</html>
