<?php

include '../components/connect.php';

session_start(); //start session

$admin_id = $_SESSION['admin_id']; //get session id from session and stores in session_id

if(!isset($admin_id)){ //if admin not set
    header('location:admin_login.php'); //back to log in page 
}



if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $delete_admins = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
    $delete_admins->execute([$delete_id]);
    header('location:admin_accounts.php');
}
 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Account</title>

    <!-- font awesome link  -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

     <!-- custom css link -->
    <link rel="stylesheet" href="../css/admin_style.css">

</head>


<body>

<?php include '../components/admin_header.php'; ?>
    
<!-- Admin accounts section  -->

<section class="accounts">

    <h1 class="heading">Admin Accounts</h1>
    

    <div class="box-container">


        <div class="box">
            <p>Add New Admin</p>
            <a href="register_admin.php" class="option-btn">Register Admin</a>
        </div>

        <?php
            $select_accounts = $conn->prepare("SELECT * FROM `admins`");
            $select_accounts->execute();
            if($select_accounts->rowCount() > 0){
                while($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)){

                
        ?>

        <div class="box">

        <p> Admin ID: <span><?= $fetch_accounts['id']; ?></span> </p>
        <p> Admin Name: <span><?= $fetch_accounts['name']; ?></span> </p>

        <div class="flex-btn">
            <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('delete this account?')" class="delete-btn">delete</a>
            <?php
                if($fetch_accounts['id'] == $admin_id){
                echo '<a href="update_profile.php" class="option-btn">update</a>';
                }
            ?>
      </div>

        </div>


        <?php
        
            }
         }else{
              echo '<p class="empty">no accounts available!</p>';
            } 

        ?>

    </div>

</section>





<!-- js file link -->
 <script src="../js/admin_script.js"></script>

</body>
</html>
