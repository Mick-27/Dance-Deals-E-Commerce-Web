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
    <title>Shop</title>

    <!-- font awesome link -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- css file link -->
     <link rel="stylesheet" href="css/style.css">

</head>
<body>
    
<?php include 'components/user_header.php';  ?>

<!---------------- shop section----------------------------->


<section class="products">

   <h1 class="heading">latest products</h1>

   <div class="box-container">

      <?php
      $select_products = $conn->prepare("
      (SELECT id, name, price, image_01, quantity  FROM `products`)
      UNION
      (SELECT id, name, price, image_01, quantity FROM `sellers_products`)
      ORDER BY id DESC
         ");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
         <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
         <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
         <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
         <div class="name"><?= $fetch_product['name']; ?></div>
         <div class="flex">
            <div class="price"><span>R</span><?= $fetch_product['price']; ?><span></span></div>
            <div class="qty"> <?= $fetch_product['quantity']; ?>  Available</div>
            
         </div>
         <input type="submit" value="add to cart" class="btn" name="add_to_cart">
      </form>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet</p>';
   }
   ?>

   </div>

</section>





<?php include 'components/footer.php'; ?>

<!-- js fie link -->
<script src="js/script.js"></script>

</body>
</html>