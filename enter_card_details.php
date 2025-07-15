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

// Check if order details are present in session
if (!isset($_SESSION['order_details'])) {
    $message[] = 'No order details found. Please go back to checkout.';
    // Or redirect back to checkout.php
    header('location:checkout.php');
    exit();
}

$order_details = $_SESSION['order_details'];

// Process credit card form submission
if (isset($_POST['process_card'])) {

    // Sanitize and filter inputs
    $card_number = filter_var($_POST['card_number'], FILTER_SANITIZE_STRING);
    $card_holder_name = filter_var($_POST['card_holder_name'], FILTER_SANITIZE_STRING);
    $expiry_month = filter_var($_POST['expiry_month'], FILTER_SANITIZE_STRING);
    $expiry_year = filter_var($_POST['expiry_year'], FILTER_SANITIZE_STRING);
    $cvv = filter_var($_POST['cvv'], FILTER_SANITIZE_STRING);

    // --- CRITICAL SECURITY NOTE: DO NOT STORE RAW CARD DETAILS ON YOUR SERVER ---
    // --- In a real application, you would send these details to a payment gateway API ---
    // --- The gateway handles the actual transaction and returns success/failure ---

    // For demonstration, simulate payment success
    $payment_successful = true; // Replace with actual gateway response

    if ($payment_successful) {
        // Retrieve order details from session
        $name = $order_details['name'];
        $number = $order_details['number'];
        $email = $order_details['email'];
        $method = $order_details['method']; // Should be 'credit card'
        $address = $order_details['address'];
        $total_products = $order_details['total_products'];
        $total_price = $order_details['total_price'];

        // START OF CHANGES
        $is_seller_order = $order_details['is_seller_order'] ?? false; // Retrieve the flag
        $seller_id = $order_details['seller_id'] ?? null; // Retrieve the seller_id

        // Conditionally insert order into the correct table
        if ($is_seller_order) {
            // Insert into `sellers_orders` for seller products
            // Ensure `sellers_orders` table has a `seller_id` column
            $insert_order = $conn->prepare("INSERT INTO `sellers_orders`(user_id, seller_id, name, number, email, method, address, total_products, total_price, placed_on, payment_status) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
            $insert_order->execute([$user_id, $seller_id, $name, $number, $email, $method, $address, $total_products, $total_price, date('Y-m-d'), 'completed']);
        } else {
            // Insert into `orders` for admin products
            $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on, payment_status) VALUES(?,?,?,?,?,?,?,?,?,?)");
            $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price, date('Y-m-d'), 'completed']);
        }
        // END OF CHANGES

        // Clear the cart
        $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart->execute([$user_id]);

        // Clear order details from session
        unset($_SESSION['order_details']);

        $message[] = 'Order placed and payment successful!';
        header('location: order_confirmed.php'); // Redirect to a success page
        exit();

    } else {
        $message[] = 'Payment failed. Please try again or choose another method.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Card Details</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="checkout-form">

    <h1 class="heading">Enter Credit Card Details</h1>

    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<p class="message">' . $msg . '</p>';
        }
    }
    ?>

    <form action="" method="post">
        <div class="display-order">
            <p>Total Products: <span><?= $order_details['total_products']; ?></span></p>
            <p>Total Price: <span>$<?= $order_details['total_price']; ?>/-</span></p>
            <p>Shipping Address: <span><?= $order_details['address']; ?></span></p>
        </div>

        <div class="flex">
            <div class="inputBox">
                <span>Card Number :</span>
                <input type="text" name="card_number" placeholder="e.g., 1234 5678 9012 3456" required class="box" maxlength="19" pattern="\d{4} ?\d{4} ?\d{4} ?\d{4}">
            </div>
            <div class="inputBox">
                <span>Cardholder Name :</span>
                <input type="text" name="card_holder_name" placeholder="e.g., John Doe" required class="box" maxlength="50">
            </div>
            <div class="inputBox">
                <span>Expiry Month (MM) :</span>
                <input type="number" name="expiry_month" placeholder="MM" required class="box" min="1" max="12" onkeypress="if(this.value.length == 2) return false;">
            </div>
            <div class="inputBox">
                <span>Expiry Year (YYYY) :</span>
                <input type="number" name="expiry_year" placeholder="YYYY" required class="box" min="<?= date('Y'); ?>" max="<?= date('Y') + 10; ?>" onkeypress="if(this.value.length == 4) return false;">
            </div>
            <div class="inputBox">
                <span>CVV :</span>
                <input type="number" name="cvv" placeholder="e.g., 123" required class="box" min="100" max="9999" onkeypress="if(this.value.length == 3 || this.value.length == 4) return false;">
            </div>
        </div>

        <input type="submit" value="Process Payment" class="btn" name="process_card">
    </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>