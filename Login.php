<?php
   include("Config.php");
   session_start();
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
      $myusername = mysqli_real_escape_string($db,$_POST['username']);
      $mypassword = mysqli_real_escape_string($db,$_POST['password']); 

      if (!preg_match("/^[a-zA-Z]{8,12}$/D",$myusername)) {
      $nameErr = "Only letters are allowed and the minimum number of the letters are 8";
      $result = mysqli_stmt_get_result($stmt);
      echo $nameErr;
      header("location:login.php");
   }


      /////////////////////////////////////////////////////////////////////////////////////
      //prepared sql statement
      // create a prepared statement 
      $stmt = mysqli_stmt_init($db);
      $safe_query  = "CALL Second(?, ?)";
      mysqli_stmt_prepare($stmt,$safe_query);
      mysqli_stmt_bind_param($stmt, "ss", $myusername, $mypassword);
      // execute query //
      mysqli_stmt_execute($stmt);
      // bind result variables 
      ////////////////////////////////////////////////////////////////////////////////////////

      $result = mysqli_stmt_get_result($stmt);
if($result ){
	while($row=mysqli_fetch_assoc($result)){
		$storedUsername= $row['username'];
		$storedPassword= $row['password'];
	}
}

if(isset($storedPassword)){
if($mypassword != $storedPassword){
	header("location:login.php");

} }


  if(isset($storedUsername)){ 
   if($myusername == $storedUsername){
	   if($mypassword == $storedPassword){

      

      $count = mysqli_num_rows($result);
      
      // If result matched $myusername and $mypassword, table row must be 1 row
		
      if($count == 1) {
         $_SESSION['login_user'] = $myusername;
         
         header("location: welcome.php");
      }
      else {
         $error = "Your Login Name or Password is invalid";
      }
   }
   }
}
   }
?>
<html>
   
   <head>
      <title>Login Page</title>
      
      <style type = "text/css">
         body {
            font-family:Arial, Helvetica, sans-serif;
            font-size:14px;
         }
         label {
            font-weight:bold;
            width:100px;
            font-size:14px;
         }
         .box {
            border:#666666 solid 1px;
         }
      </style>
      
   </head>
   
   <body bgcolor = "#FFFFFF">
	
      <div align = "center">
         <div style = "width:300px; border: solid 1px #333333; " align = "left">
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login</b></div>
				
            <div style = "margin:30px">
               
               <form action = "" method = "POST">
                  <label>UserName  :</label><input type = "text" name = "username" class = "box"/><br /><br />
                  <label>Password  :</label><input type = "password" name = "password" class = "box" /><br/><br />
                  <input type = "submit" value = " Submit "/><br />
               </form>
               
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"></div>
					
            </div>
				
         </div>
			
      </div>

   </body>
</html>