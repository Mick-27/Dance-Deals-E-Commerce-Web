<?php

    include 'components/connect.php';

    session_start();

    if(isset($_SESSION ['user_id'])){
        $user_id = $_SESSION ['user_id'];
    }
    else{
        $user_id = '';
        header('location:user_login.php');
    };

 if(isset($_POST['order'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = 'flat/house no. '. $_POST['flat'] .', '. $_POST['street'] .', '. $_POST['city'] .', '. $_POST['province'] .', '. $_POST['country'] .' - '. $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){
      // Determine if the order contains seller products or admin products
      $is_seller_order = false;
      $seller_id = null; // Initialize seller_id

      // Fetch all items from the cart to check their origin
      $cart_items_for_check = $conn->prepare("SELECT pid FROM `cart` WHERE user_id = ?");
      $cart_items_for_check->execute([$user_id]);

      while ($cart_item = $cart_items_for_check->fetch(PDO::FETCH_ASSOC)) {
          $pid = $cart_item['pid'];

          // Check if the product is in sellers_products
          $check_seller_product = $conn->prepare("SELECT seller_id FROM `sellers_products` WHERE id = ?"); // Assuming sellers_products has seller_id
          $check_seller_product->execute([$pid]);

          if ($check_seller_product->rowCount() > 0) {
              $is_seller_order = true;
              // For simplicity, we'll take the seller_id of the first seller product found.
              // In a real multi-seller scenario, you'd need to handle orders for multiple sellers differently (e.g., split orders).
              $seller_id = $check_seller_product->fetch(PDO::FETCH_ASSOC)['seller_id'];
              break; // If any product is a seller product, treat the whole order as a seller order for this prototype
          }
      }

      if ($method == 'credit card') {
          // Store order details in session to pass to next page
          $_SESSION['order_details'] = [
              'name' => $name,
              'number' => $number,
              'email' => $email,
              'method' => $method,
              'address' => $address,
              'total_products' => $total_products,
              'total_price' => $total_price,
              'is_seller_order' => $is_seller_order, // Pass this flag
              'seller_id' => $seller_id // Pass seller_id if it's a seller order
          ];
          header('location: enter_card_details.php'); // Redirect to new page for card details
          exit(); // Stop script execution
      } else {
          if ($is_seller_order) {
             // Insert into `sellers_orders` for seller products
             // Ensure `sellers_orders` has a `seller_id` column
             $insert_order = $conn->prepare("INSERT INTO `sellers_orders`(user_id, seller_id, name, number, email, method, address, total_products, total_price, placed_on, payment_status) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
             $insert_order->execute([$user_id, $seller_id, $name, $number, $email, $method, $address, $total_products, $total_price, date('Y-m-d'), 'pending']);
          } else {
             // Insert into `orders` for admin products
             $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on, payment_status) VALUES(?,?,?,?,?,?,?,?,?,?)");
             $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price, date('Y-m-d'), 'pending']);
          }

          $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
          $delete_cart->execute([$user_id]);

          $message[] = 'order placed successfully!';
          header('location: order_confirmed.php');
          exit();
      }
   }else{
      $message[] = 'your cart is empty';
   }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <!-- font awesome link -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- css file link -->
     <link rel="stylesheet" href="css/style.css">

</head>
<body>
    
<?php include 'components/user_header.php';  ?>

<!--------------------------- checkout section  ------------------------------>



<section class="checkout">

    <h1 class="heading">Your Items</h1>

    <div class="display-orders">

    <?php
      $grand_total = 0;
      $cart_items[] = '';
      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart->execute([$user_id]);
      if($select_cart->rowCount() > 0){
         while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
        $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
        $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['quantity'].') -';
        $total_products = implode($cart_items);
    ?>
        <p> <?= $fetch_cart['name']; ?> <span>(<?= 'R'.$fetch_cart['price'].' x '. $fetch_cart['quantity']; ?>)</span> </p>
    <?php
  
        }
        }else{
            echo '<p class="empty">your cart is empty</p>';
        }
    ?>



   

    </div>

    
    <p class="grand-total">Total : <span>R<?= $grand_total; ?></span></p>

    <form action="" method="post">

         <h1 class="heading">Place Your Order</h1>

        <input type="hidden" name="total_products" value="<?= $total_products; ?>">
        <input type="hidden" name="total_price" value="<?= $grand_total; ?>">


        <div class="flex">


        <!--------------------------- Personal info  ------------------------------>
            <div class="inputBox">
                <span>Your name :</span>
                <input type="text" maxlength="20" placeholder="Enter your Name" required class="box" name="name">
            </div>

            <div class="inputBox">
                <span>Your number :</span>
                <input type="number" min="0" max= "9999999999" onkeypress= "if(this.value.length == 10) return false;" placeholder="Enter your Number" required class="box" name="number" >
            </div>

            <div class="inputBox">
                <span>Your email :</span>
                <input type="email" maxlength="20" placeholder="Enter your email" required class="box" name="email">
            </div>
            
            <!--------------------------- Payment  ------------------------------>
            
            <div class="inputBox">
                <span>Payment Method :</span>
                <select name="method" class="box" required>

                <option value="cash on delivery">cash on delivery</option>
                <option value="credit card">credit card</option>
                <option value="paypal">paypal</option>

                </select>
            </div>


            <!--------------------------- address  ------------------------------>
            <div class="inputBox">
                <span>Address Line 1 :</span>
                <input type="text" maxlength="50" placeholder="e.g. flat no." required class="box" name="flat">
            </div>

            <div class="inputBox">
                <span>Address Line 2 :</span>
                <input type="text" maxlength="50" placeholder="e.g. street name" required class="box" name="street">
            </div>

            <div class="inputBox">
                <span>City :</span>
                <input type="text" maxlength="50" placeholder="e.g. Bloemfontein" required class="box" name="city">
            </div>

             <div class="inputBox">
                <span>Province :</span>
                <input type="text" maxlength="50" placeholder="e.g. Free-State" required class="box" name="province">
            </div>

             <div class="inputBox">
                <span>Country :</span>
                <input type="text" maxlength="50" placeholder="e.g. South Africa" required class="box" name="country">
            </div>

             <div class="inputBox">
                <span>Postal Code :</span>
                <input type="number" min="0" max= "99999" onkeypress= "if(this.value.length == 4) return false;" placeholder="e.g. 9430" required class="box" name="pin_code">
            </div>
        </div>



            <input type="submit" value="place order" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>" name="order">



    </form>

</section>





<?php include 'components/footer.php'; ?>

<!-- js fie link -->
<script src="js/script.js"></script>

</body>
</html>