<?php

include '../components/connect.php'; //include contents of connect file

session_start(); //starts or resumes new session 

    if(isset($_SESSION ['seller_id'])){
        $seller_id = $_SESSION ['seller_id'];
    }
    else{
        $seller_id = '';
    }




    if (isset($_SESSION['registration_success'])) {
        echo '<div class="success-message">' . $_SESSION['registration_success'] . '</div>';
        unset($_SESSION['registration_success']); // Clear the session variable so it only shows once
    }


    if(isset($_POST['submit'])){ //if button is clicked

        $name = $_POST['name'];  //retrieve name in username field
        $name = filter_var($name, FILTER_SANITIZE_STRING); //sanitizes line
        $pass = sha1($_POST['pass']); //retrieve pass
        $pass = filter_var($pass, FILTER_SANITIZE_STRING); //sanitize line
     
        $select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE name = ? AND password = ?"); //select the matching admin
        $select_seller->execute([$name, $pass]); //execute query
        $row = $select_seller->fetch(PDO::FETCH_ASSOC); //fetch result 
     
        if($select_seller->rowCount() > 0){
           $_SESSION['seller_id'] = $row['id'];
           header('location:dashboard.php');
           
        }else{
           $message[] = 'incorrect username or password!';
        }
     
     }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>

    <!-- font awesome link  -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

     <!-- custom css link -->
      <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
    



<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<!-- admin log in form section  -->

<section class="form-container">

   <form action="" method="post">
      <h3>login now</h3>
      <!--<p>default username = <span>admin</span> & password = <span>111</span></p>-->
      <input type="text" name="name" required placeholder="Enter your username" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Enter your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Login" class="btn" name="submit">
   </form>

</section>


<!-- admin log in form section ends -->








</body>
</html>