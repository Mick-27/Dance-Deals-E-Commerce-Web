<?php

include '../components/connect.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if(!isset($seller_id)){
    header('location:seller_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- font awesome link  -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

     <!-- custom css link -->
      <link rel="stylesheet" href="../css/admin_style.css">

</head>


<body>
    
<?php
    include '../components/seller_header.php'
?>

<!-- seller dashboard -->

<section class="dashboard">

    <h1 class="heading">Dashboard</h1>

   <div class="box-container">

        <div class="box">
            <h3>Welcome</h3>
            <p><?= $fetch_profile['name']; ?></p>
            <a href="update_profile.php" class="btn">Update Profile</a>
                
        </div>

        <!-- orders -->
        <div class="box">
            <?php
                $total_pendings = 0;
                $select_pendings = $conn->prepare("SELECT * FROM `sellers_orders` WHERE payment_status = ?");
                $select_pendings->execute(['pending']);
                if($select_pendings->rowCount() > 0)
                {
                while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC))
                {
                    $total_pendings += $fetch_pendings['total_price'];
                }
                }
            ?>

            <h3><span>R</span><?= $total_pendings; ?><span>.00</span></h3>
            <p>Total Pendings</p>
            <a href="placed_orders.php" class="btn">View New Orders</a>
        </div>



        <div class="box">
            <?php
                $total_completes = 0;
                $select_completes = $conn->prepare("SELECT * FROM `sellers_orders` WHERE payment_status = ?");
                $select_completes->execute(['completed']);
                if($select_completes->rowCount() > 0){
                while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
                    $total_completes += $fetch_completes['total_price'];
                }
                }
            ?>

            <h3><span>R</span><?= $total_completes; ?><span>.00</span></h3>
            <p>Completed Orders</p>
            <a href="placed_orders.php" class="btn">View Completed Orders</a>
        </div>

        <div class="box">

            <?php
                    $select_orders = $conn->prepare("SELECT * FROM `sellers_orders`");
                    $select_orders->execute();
                    $number_of_orders = $select_orders->rowCount()
            ?>
            <h3><?= $number_of_orders; ?></h3>
            <p>Orders Placed</p>
            <a href="placed_orders.php" class="btn">View All Orders</a>

        </div>


        <div class="box">
            <?php
                $select_products = $conn->prepare("SELECT * FROM `sellers_products`");
                $select_products->execute();
                $number_of_products = $select_products->rowCount()
            ?>
            <h3><?= $number_of_products; ?></h3>
            <p>Products Added</p>
            <a href="products.php" class="btn">View Products</a>
        </div>


    </div>

</section>

<!-- Seller dashboard ends-->






<!-- js file link -->
 <script src="../js/admin_script.js"></script>

</body>
</html>