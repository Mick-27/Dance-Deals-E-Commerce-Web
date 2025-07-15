<?php
include 'components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:user_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="order-confirmed">
    <div class="box">
        <p class="icon"><i class="fas fa-check-circle"></i></p>
        <h1 class="heading">Order Placed Successfully!</h1>
        <p class="message">Thank you for your purchase. Your order has been confirmed.</p>
        <a href="orders.php" class="btn">View Your Orders</a>
        <a href="home.php" class="option-btn">Continue Shopping</a>
    </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>