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
    <title>Search Page</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    
<?php include 'components/user_header.php';  ?>


<section class="search-form">
   <form action="" method="post">
      <input type="text" name="search_box" placeholder="search here..." maxlength="100" class="box" required>
      <button type="submit" class="fas fa-search" name="search_btn"></button>
   </form>
</section>

<section class="products" style="padding-top: 0; min-height:100vh;">
   
   <div class="box-container">

   <?php
     if(isset($_POST['search_box']) || isset($_POST['search_btn'])){ // Use || for OR
        $search_box = $_POST['search_box'];
        // Prepare the search term for LIKE clause with wildcards
        $search_term = '%' . $search_box . '%';

        // Use UNION ALL to combine results from both tables
        // IMPORTANT: Select the EXACT SAME COLUMNS in the EXACT SAME ORDER
        // If column names differ in `sellers_products`, use AS to alias them
        // Example: `seller_product_name AS name`, `seller_product_price AS price`
        $select_products = $conn->prepare("
            SELECT id, name, price, image_01 FROM `products` WHERE name LIKE ?
            UNION ALL
            SELECT id, name, price, image_01 FROM `sellers_products` WHERE name LIKE ?
        ");
        
        // Execute the prepared statement, passing the search term for each placeholder
        $select_products->execute([$search_term, $search_term]); 

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
         <div class="price"><span>$</span><?= $fetch_product['price']; ?><span>/-</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
            }
        }else{
            echo '<p class="empty">no products found!</p>';
        }
     } // End if(isset($_POST['search_box']) || isset($_POST['search_btn']))
   ?>

   </div>

</section>

<?php include 'components/footer.php'; ?> <script src="js/script.js"></script>

</body>
</html>