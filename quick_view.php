<?php

    include 'components/connect.php';

    session_start();

    if(isset($_SESSION ['user_id'])){
        $user_id = $_SESSION ['user_id'];
    }
    else{
        $user_id = '';
    }

    include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick View</title>

    <!-- font awesome link -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- css file link -->
     <link rel="stylesheet" href="css/style.css">

</head>
<body>
    
<?php include 'components/user_header.php';  ?>

<!-- Quick view section k -->
<section class="quick-view">

   <h1 class="heading">quick view</h1>

   <?php
    $pid = $_GET['pid'];

    $select_products = $conn->prepare("
        SELECT id, name, price, quantity, image_01, image_02, image_03, details 
        FROM products 
        WHERE id = ?
        UNION
        SELECT id, name, price, quantity, image_01, image_02, image_03, details 
        FROM sellers_products 
        WHERE id = ?
    ");
    
    $select_products->execute([$pid, $pid]);
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">

      <div class="row">
            <div class="image-container">
                <div class="main-image">
                    <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
                </div>
                <div class="sub-image">
                    <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
                    <img src="uploaded_img/<?= $fetch_product['image_02']; ?>" alt="">
                    <img src="uploaded_img/<?= $fetch_product['image_03']; ?>" alt="">
                </div>
            </div>
            <div class="content">
                <div class="name"><?= $fetch_product['name']; ?></div>
                <div class="flex">
                    <div class="price"><span>R</span><?= $fetch_product['price']; ?><span></span></div>
                    <!--<input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">-->
                    <div class="quantity"> <?= $fetch_product['quantity'] ?? 1 ?> Available</div>
                </div>
                <div class="details"><?= $fetch_product['details']; ?></div>

                <div class="flex-btn">
                    <input type="submit" value="add to cart" class="btn" name="add_to_cart">
                    <input class="option-btn" type="submit" name="add_to_wishlist" value="add to wishlist">
                </div>
            </div>
      </div>
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

</section>













<?php include 'components/footer.php'; ?>

<!-- js fie link -->
<script src="js/script.js"></script>

</body>
</html>