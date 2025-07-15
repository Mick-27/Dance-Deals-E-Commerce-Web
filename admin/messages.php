<?php

include '../components/connect.php';

session_start(); //start session

$admin_id = $_SESSION['admin_id']; //get session id from session and stores in session_id

if(!isset($admin_id)){ //if admin not set
    header('location:admin_login.php'); //back to log in page 
}

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
    $delete_message->execute([$delete_id]);
    header('location:messages.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>

    <!-- font awesome link  -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

     <!-- custom css link -->
      <link rel="stylesheet" href="../css/admin_style.css">

</head>


<body>
    
<?php include '../components/admin_header.php'; ?>

<!-- Messages section  -->

<section class="messages">

    <h1 class="heading">messages</h1>

    <div class="box-container">

        <?php
            $select_messages = $conn->prepare("SELECT * FROM `messages`");
            $select_messages->execute();
            if($select_messages->rowCount() > 0){
                while($fetch_message = $select_messages->fetch(PDO::FETCH_ASSOC)){
        ?>
        <div class="box">
            <p> User ID : <span><?= $fetch_message['user_id']; ?></span></p>
            <p> Name : <span><?= $fetch_message['name']; ?></span></p>
            <p> Email : <span><?= $fetch_message['email']; ?></span></p>
            <p> Number : <span><?= $fetch_message['number']; ?></span></p>
            <p> Message : <span style="color: #419bd6;"><?= $fetch_message['message']; ?></span></p> 
            <a href="messages.php?delete=<?= $fetch_message['id']; ?>" onclick="return confirm('delete this message?');" class="delete-btn">delete</a>
        </div>
        <?php
            }
            }else{
                echo '<p class="empty">you have no messages</p>';
            }
        ?>

    </div>

</section>




<!-- js file link -->
 <script src="../js/admin_script.js"></script>

</body>
</html>