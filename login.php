
<?php
include('database_connection.php');
if(isset($_SESSION["type"]))
{
 header("location: index.php");
}
$message = '';

if(isset($_POST["login"]))
{
 if(empty($_POST["user_email"]) || empty($_POST["user_password"]))
 {
  $message = "<label>Both Fields are required</label>";
 }
 else
 {
  $query = "
  SELECT * FROM user_details 
  WHERE user_email = :user_email";
  $statement = $connect->prepare($query);
  $statement->execute(
   array(
    'user_email' => $_POST["user_email"]
   )
  );
  $count = $statement->rowCount();
  if($count > 0)
  {
   $result = $statement->fetchAll();
   foreach($result as $row)
   {
    if(password_verify($_POST["user_password"], $row["user_password"]))
    {
     $insert_query = "
     INSERT INTO login_details (
      user_id, last_activity) VALUES (
      :user_id, :last_activity)
     ";
     $statement = $connect->prepare($insert_query);
     $statement->execute(
      array(
       'user_id'  => $row["user_id"],
       'last_activity' => date("Y-m-d H:i:s", STRTOTIME(date('h:i:sa')))
      )
     );
     $login_id = $connect->lastInsertId();
     if(!empty($login_id))
     {
      $_SESSION["type"] = $row["user_type"];
      $_SESSION["login_id"] = $login_id;
      header("location: index.php");
     }
    }
    else
    {
     $message = "<label>Wrong Password</label>";
    }
   }
  }
  else
  {
   $message = "<label>Wrong Email Address</labe>";
  }
 }
}


?>

<!DOCTYPE html>
<html>



  <head>
  <title>Registration</title>
  <link rel="stylesheet" type="text/css" >
  <style>
    * {
  margin: 0px;
  padding: 0px;
}
body {
  font-size: 120
  background-color: #808000;

}

.header {
  width: 30%;
  margin: 50px auto 0px;
  color: red;
 
  opacity:0.6;
  text-align: center;
  border: 0px  ;
  
  border-radius: 10px 10px 0px 0px;
  padding: 20px;
}
form, .content {
  width: 30%;
  margin: 0px auto;
  padding: 20px;
  border: 0px ;
  background: white;
  border-radius: 0px 0px 10px 10px;
}
.input-group {
  margin: 10px 0px 10px 0px;
}
.input-group label {
  display: block;
  text-align: left;
  margin: 3px;
}
.input-group input {
  height: 30px;
  width: 93%;
  padding: 5px 10px;
  font-size: 16px;
  border-radius: 5px;
  border: 1px solid gray;
}
.btn {
  padding: 10px;
  font-size: 15px;
  color: white;
  background:#F1C40F ;
  border: none;
  border-radius: 5px;
}
.error {
  width: 92%; 
  margin: 0px auto; 
  padding: 10px; 
  border: 1px solid #a94442; 
  color: #a94442; 
  background: #f2dede; 
  border-radius: 5px; 
  text-align: left;
}
.success {
  color: #3c763d; 
  background: #dff0d8; 
  border: 0px solid #3c763d;
  margin-bottom: 20px;
}
.f1{
	background-image: url("bg_image.jpg");
	background-repeat:no-repeat;
	background-attachment: fixed;
   opacity:0.8;
  background-size: 100% 100%;
}
  </style>
</head>
<body>
  <div class="header">
  	<h2>Login</h2>
  </div>
	 
  <form method="post" class="f1">
<span class="text-danger"><?php echo $message; ?></span>
  	<div class="input-group">
  		<label>UserEmail</label>
  		<input type="text" name="user_email" >
  	</div>
  	<div class="input-group">
  		<label>UserPassword</label>
  		<input type="password" name="user_password">
  	</div>
  	<div class="input-group">
  		<button type="submit" class="btn" name="login">Login</button>
  	</div>
  	
  </form>
  <br />
 
</body>

 
</html>
