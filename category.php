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
    <title>Category</title>

    <!-- font awesome link -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- css file link -->
     <link rel="stylesheet" href="css/style.css">

</head>
<body>
    
<?php include 'components/user_header.php';  ?>


 <!------------------ category section  ----------------------->

<section class="products">

    <?php
    
        //$category = isset($_GET['category']) ? trim($_GET['category']) : '';

         
    
        // Retrieve and sanitize the category from the URL
        // It's good practice to use a default or handle the case where 'category' isn't set.
        $category = isset($_GET['category']) ? htmlspecialchars(trim($_GET['category'])) : '';

    ?>

    

   <!--<h1 class="heading">category</h1>-->
   <h1 class="heading">Category: <?= htmlspecialchars($category); ?></h1> <!-- Displays the clicked category -->
   
   <div class="box-container">

        <?php
            $category = $_GET['category']; //retrieves category name 
             $select_products = $conn->prepare("
                    SELECT * FROM `products` WHERE name LIKE '%{$category}%'
                    UNION 
                    SELECT * FROM `sellers_products` WHERE name LIKE '%{$category}%'
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
                <div class="quantity"> <?= $fetch_product['quantity'] ?? 1 ?> Available</div>
                <!--<input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1"> -->
            </div>
            <input type="submit" value="add to cart" class="btn" name="add_to_cart">


        </form>

        <?php
            }
        }else{
            echo '<p class="empty">no products Added Yet!</p>';
        }
        ?>
    </div>

</section>



<?php include 'components/footer.php'; ?>

<!-- js fie link -->
<script src="js/script.js"></script>

</body>
</html>