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
    <title>Home</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

    <!-- font awesome link -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- css file link -->
     <link rel="stylesheet" href="css/style.css">

</head>
<body>
    
<?php include 'components/user_header.php';  ?>


<!------------------slider section ------------------------------------------ -->

<div class="home-bg">

    <section class="swiper home-slider">

        <div class="swiper-wrapper">


            <div class="swiper-slide slide">

            <div class="image">
                <img src="images/dress1.png" alt="">
            </div>

                <div class="content">
                    <span>Up to 60% off</span>
                    <h3>Latest Dresses</h3>
                    <a href="category.php?category=dress" class="btn">Shop Now!</a>
                </div>
            </div>



            <div class="swiper-slide slide">

            <div class="image">
                <img src="images/leo1.png" alt="">
            </div>

                <div class="content">
                    <span>Up to 50% off</span>
                    <h3>Leotards</h3>
                    <a href="category.php?category=leotard" class="btn">Shop Now!</a>
                </div>
            </div>


            <div class="swiper-slide slide">

                <div class="image">
                    <img src="images/shoe.png" alt="">
                </div>

                <div class="content">
                    <span>Up to 45% off</span>
                    <h3>Latest Shoes</h3>
                    <a href="category.php?category=shoes" class="btn">Shop Now!</a>
                </div>
            </div>

        </div>

        <div class="swiper-pagination"></div>

    </section>

</div>




<!------------------ category section -------------------------------------------->

<section class="home-category">

<h1 class="heading">Shop by Category</h1>


    <div class="swiper category-slider">

        <div class="swiper-wrapper">

            <a href="category.php?category=leotards" class="swiper-slide slide">
                <img src="images/leotard-icon.png" alt="" height="200" width="200">
                <h3>Leotards</h3>
            </a>

            <a href="category.php?category=dress" class="swiper-slide slide">
                <img src="images/dress-icon.png" alt="" height="300" width="300">
                <h3>Dresses</h3>
            </a>

            <a href="category.php?category=tops" class="swiper-slide slide">
                <img src="images/shirt-icon.png" alt="" height="200" width="200">
                <h3>Tops</h3>
            </a>

            <a href="category.php?category=skirt" class="swiper-slide slide">
                <img src="images/skirt.png" alt="" height="200" width="200">
                <h3>Skirts</h3>
            </a>

            <a href="category.php?category=shoes" class="swiper-slide slide">
                <img src="images/shoe-icon.png" alt="" height="200" width="200">
                <h3>Shoes</h3>
            </a>

            <a href="category.php?category=two-piece" class="swiper-slide slide">
                <img src="images/7.png" alt="" height="200" width="200">
                <h3>Two Piece</h3>
            </a>


            <a href="category.php?category=hoodie" class="swiper-slide slide">
                <img src="images/hoodie-icon.png" alt="" height="200" width="200">
                <h3>Hoodies</h3>
            </a>

            
            <a href="category.php?category=bag" class="swiper-slide slide">
                <img src="images/bag-icon.png" alt="" height="200" width="200">
                <h3>Bags</h3>
            </a>

            <a href="category.php?category=accessories" class="swiper-slide slide">
                <img src="images/acc-icon.png" alt="" height="200" width="200">
                <h3>Accessories</h3>
            </a>

            

            

        </div>

        <div class="swiper-pagination"></div>

    </div>



</section>


<!------------------ products section -------------------------------------------->

<section class="home-products">

    <h1 class="heading">Latest Products</h1>

    <div class="swiper products-slider">

        <div class="swiper-wrapper">

            <?php
                $select_products = $conn->prepare("
                    SELECT id, name, price, image_01, quantity FROM products 
                    UNION 
                    SELECT id, name, price, image_01, quantity FROM sellers_products 
                    LIMIT 6
                ");
                $select_products->execute();
                if($select_products->rowCount() > 0){
                while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
            ?>
            <form action="" method="post" class="slide swiper-slide">
                <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
                <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
                <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">

                <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
                <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye">
                    
                </a>
                <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
                <div class="name"><?= $fetch_product['name']; ?></div>

                <div class="flex">
                    <div class="price"><span>R</span><?= $fetch_product['price']; ?><span></span></div>
                    <div class="quantity"><?= $fetch_product['quantity'] ?? 1 ?> Available</div>

                <!--<input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1"> -->
                </div> 
                <input type="submit" value="Add to cart" class="btn" name="add_to_cart">
            </form>

            <?php
                }
            }else{
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>

        </div>

        <div class="swiper-pagination"></div>

    </div>

</section>









<?php include 'components/footer.php'; ?>


<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- js fie link -->
<script src="js/script.js"></script>

<script>

    var swiper = new Swiper(".home-slider", {
        loop:true,
        grabCursor:true,
        spaceBetween: 20,
        pagination: {
            el: ".swiper-pagination",
            clickable:true,
        },
    });

    var swiper = new Swiper(".category-slider", {
        loop:true,
        grabCursor:true,
        spaceBetween: 20,
        pagination: {
            el: ".swiper-pagination",
            clickable:true,
        },
        
        breakpoints: {
            0: {
                slidesPerView: 2,
            },
            650: {
                slidesPerView: 3,
            },
            768: {
                slidesPerView: 4,
            },
           
        },
    });

    
var swiper = new Swiper(".products-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      550: {
         slidesPerView: 2,
       },
      
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
   },
});


</script>

</body>
</html>