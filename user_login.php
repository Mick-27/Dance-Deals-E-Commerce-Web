<?php

    include 'components/connect.php';

    session_start();

    if(isset($_SESSION ['user_id'])){
        $user_id = $_SESSION ['user_id'];
    }
    else{
        $user_id = '';
    }

   


    if (isset($_SESSION['registration_success'])) {
        echo '<div class="success-message">' . $_SESSION['registration_success'] . '</div>';
        unset($_SESSION['registration_success']); // Clear the session variable so it only shows once
    }





    if(isset($_POST['submit'])){

        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);
        $pass = sha1($_POST['pass']);
        $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    
        $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
        $select_user->execute([$email, $pass]);
        $row = $select_user->fetch(PDO::FETCH_ASSOC);
    
        if($select_user->rowCount() > 0){
        $_SESSION['user_id'] = $row['id'];
        header('location:index.php');
        $message[] = 'Welcome Back!';
        }else{
        $message[] = 'Incorrect Username or Password! Register if you dont have an account';
        }
    
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- font awesome link -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- css file link -->
     <link rel="stylesheet" href="css/style.css">

</head>
<body>
    
<?php include 'components/user_header.php';  ?>

<!-- user log in section  -->


<section class="form-container">

    <form action="" method="POST">

    <h3>Welcome Back! Login to get started!</h3>
      <input type="email" name="email" required placeholder="Enter your email" maxlength="50"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">

      <input type="password" name="pass" required placeholder="Enter your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">

      <input type="submit" value="login now" class="btn" name="submit">

      <p>Don't have an account yet?</p>
      <a href="user_register.php" class="option-btn">Register now</a>

    </form>

</section>








<?php include 'components/footer.php'; ?>

<!-- js fie link -->
<script src="js/script.js"></script>

</body>
</html>