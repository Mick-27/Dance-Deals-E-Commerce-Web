<?php

    include 'components/connect.php';

    session_start();

    if(isset($_SESSION ['user_id'])){
        $user_id = $_SESSION ['user_id'];
    }
    else{
        $user_id = '';
    }

    
if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);
 
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select_user->execute([$email,]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);
 
    if($select_user->rowCount() > 0){
       $message[] = 'Email already exists!';
    }else{
       if($pass != $cpass){
          $message[] = 'confirm password not matched!';
       }else{
          $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
          $insert_user->execute([$name, $email, $cpass]);
          $_SESSION['registration_success'] = 'Welcome, Please login to get started!';
          header('location:user_login.php');
          exit();
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

    <!-- font awesome link -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- css file link -->
     <link rel="stylesheet" href="css/style.css">

</head>
<body>
    
<?php include 'components/user_header.php';  ?>


<!---------------------- Register section  ---------------->
<section class="form-container">

   <form action="" method="post">
      <h3>Welcome, Fellow Dancer! Your Perfect Fit Awaits. Register Now.</h3>
      <input type="text" name="name" required placeholder="enter your username" maxlength="20"  class="box">
      <input type="email" name="email" required placeholder="enter your email" maxlength="50"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="enter your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="confirm your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="register now" class="btn" name="submit">
      <p>Already have an Account?</p>
      <a href="user_login.php" class="option-btn">Login now</a>
   </form>

</section>




<?php include 'components/footer.php'; ?>

<!-- js fie link -->
<script src="js/script.js"></script>

</body>
</html>