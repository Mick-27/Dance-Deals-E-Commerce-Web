<?php

    include 'components/connect.php';

    session_start();

    if(isset($_SESSION ['user_id'])){
        $user_id = $_SESSION ['user_id'];
    }
    else{
        $user_id = '';
        
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    
<?php include 'components/user_header.php';  ?>

  <section class="show-orders">

         <h1 class="heading">your orders</h1>    

    <div class="box-container">


   <?php
      if($user_id == ''){
         echo '<p class="empty">please login to see your orders</p>';
      }else{
         $all_orders = [];

         // Fetch orders from the 'orders' table (for admin products)
         $select_admin_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_admin_orders->execute([$user_id]);
         while($fetch_admin_orders = $select_admin_orders->fetch(PDO::FETCH_ASSOC)){
             $all_orders[] = $fetch_admin_orders;
         }

         // Fetch orders from the 'sellers_orders' table (for seller products)
         $select_seller_orders = $conn->prepare("SELECT * FROM `sellers_orders` WHERE user_id = ?");
         $select_seller_orders->execute([$user_id]);
         while($fetch_seller_orders = $select_seller_orders->fetch(PDO::FETCH_ASSOC)){
             $all_orders[] = $fetch_seller_orders;
         }

         if(!empty($all_orders)){ // Check if any orders were found in either table
             // Optional: Sort orders by placed_on date if you want them chronological
             usort($all_orders, function($a, $b) {
                 return strtotime($b['placed_on']) - strtotime($a['placed_on']);
             });

            foreach($all_orders as $fetch_orders){ // Loop through all combined orders
   ?>
        <div class="box">
            <p>Placed On : <span><?= $fetch_orders['placed_on']; ?></span></p>
            <p>Name : <span><?= $fetch_orders['name']; ?></span></p>
            <p>Email : <span><?= $fetch_orders['email']; ?></span></p>
            <p>Number : <span><?= $fetch_orders['number']; ?></span></p>
            <p>Address : <span><?= $fetch_orders['address']; ?></span></p>
            <p>Payment Method : <span><?= $fetch_orders['method']; ?></span></p>
            <p>Your Orders : <span><?= $fetch_orders['total_products']; ?></span></p>
            <p>Total Price : <span>R<?= $fetch_orders['total_price']; ?></span></p>
            <p> Payment Status : <span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p>
        </div>
   <?php
            }
         }else{
            echo '<p class="empty">no orders placed yet!</p>';
         }
      }
   ?>

    </div>

  </section>



<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>