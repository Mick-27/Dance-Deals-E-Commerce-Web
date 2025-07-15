<?php

include '../components/connect.php';

session_start(); //start session

$admin_id = $_SESSION['admin_id']; //get session id from session and stores in session_id

if(!isset($admin_id)){ //if admin not set
   header('location:admin_login.php'); //back to log in page 
}


if(isset($_POST['submit'])){ //if button is clicked

    $name = $_POST['name'];  //retrieve name in username field
    $name = filter_var($name, FILTER_SANITIZE_STRING); //sanitizes line
    $pass = sha1($_POST['pass']); //retrieve pass
    $pass = filter_var($pass, FILTER_SANITIZE_STRING); //sanitize line
    $cpass = sha1($_POST['cpass']); //retrieve pass
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING); //sanitize line
 
    $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ?"); //select the matching admin
    $select_admin->execute([$name]); //execute query
    $row = $select_admin->fetch(PDO::FETCH_ASSOC); //fetch result 
 
    if($select_admin->rowCount() > 0){
       $message[] = 'Username already exists!';
       
    }
    else{
        if($pass != $cpass){
            $message[] = 'Password does not match';
        }
        else{
            $insert_admin = $conn->prepare("INSERT INTO `admins`(name, password) VALUES(?,?)");
            $insert_admin->execute([$name, $cpass]);
            $message[] = 'New Admin Registered Successfully!';
         }
    }
 
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- font awesome link  -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

     <!-- custom css link -->
      <link rel="stylesheet" href="../css/admin_style.css">

</head>


<body>
    
<?php
    include '../components/admin_header.php'
?>


<!-- register admin section -->


<section class="form-container">

   <form action="" method="post">
      <h3>Register </h3>
     
      <input type="text" name="name" required placeholder="Enter your Username" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Enter your Password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Confirm your Password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Register" class="btn" name="submit">
   </form>

</section>


<!-- register admin section ends-->













<!-- js file link -->
 <script src="../js/admin_script.js"></script>

</body>
</html>