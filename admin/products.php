<?php

include '../components/connect.php';

session_start(); //start session

$admin_id = $_SESSION['admin_id']; //get session id from session and stores in session_id

if(!isset($admin_id)){ //if admin not set
    header('location:admin_login.php'); //back to log in page 
};




if(isset($_POST['add_product']))
{

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);

    $quantity = $_POST['quantity'];
    $quantity = filter_var($quantity, FILTER_SANITIZE_NUMBER_INT);
    
    $details = $_POST['details'];
    $details = filter_var($details, FILTER_SANITIZE_STRING);


    
    $image_01 = $_FILES['image_01']['name'];
    $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
    $image_size_01 = $_FILES['image_01']['size'];
    $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
    $image_folder_01 = '../uploaded_img/'.$image_01;


    $image_02 = $_FILES['image_02']['name'];
    $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
    $image_size_02 = $_FILES['image_02']['size'];
    $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
    $image_folder_02 = '../uploaded_img/'.$image_02;

    $image_03 = $_FILES['image_03']['name'];
    $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
    $image_size_03 = $_FILES['image_03']['size'];
    $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
    $image_folder_03 = '../uploaded_img/'.$image_03;

    $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
    $select_products->execute([$name]);

    if($select_products->rowCount() > 0)
    {
        $message[] = 'Product name already exists!';
    }
    else
    {

        $insert_products = $conn->prepare("INSERT INTO `products`(name, details, price, quantity, image_01, image_02, image_03) VALUES(?,?,?,?,?,?,?)");
        $insert_products->execute([$name, $details, $price, $quantity, $image_01, $image_02, $image_03]);
  
        if($insert_products) //check image size
        {
           if($image_size_01 > 2000000 OR $image_size_02 > 2000000 OR $image_size_03 > 2000000)
           {
              $message[] = 'Image size is too large!'; //if image too big
           }
           else //else add product image
            {
              move_uploaded_file($image_tmp_name_01, $image_folder_01);
              move_uploaded_file($image_tmp_name_02, $image_folder_02);
              move_uploaded_file($image_tmp_name_03, $image_folder_03);
              $message[] = 'New Product Added!';
            }
  
        }
  
     }  

};


if(isset($_GET['delete'])){

    $delete_id = $_GET['delete'];
    $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
    $delete_product_image->execute([$delete_id]);
    $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
    unlink('../uploaded_img/'.$fetch_delete_image['image_01']);
    unlink('../uploaded_img/'.$fetch_delete_image['image_02']);
    unlink('../uploaded_img/'.$fetch_delete_image['image_03']);
    $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
    $delete_product->execute([$delete_id]);
    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
    $delete_cart->execute([$delete_id]);
    $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
    $delete_wishlist->execute([$delete_id]);
    header('location:products.php');
 }
 







?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>

    <!-- font awesome link  -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

     <!-- custom css link -->
      <link rel="stylesheet" href="../css/admin_style.css">

</head>


<body>
    
<?php include '../components/admin_header.php'; ?>

<!-- add products  -->

<section class="add-products">

    <h1 class="heading">Add Product</h1>

    <form action="" method="post" enctype="multipart/form-data">


        <div class="flex">

            <div class="inputBox">
                <span>Product Name (Required)</span>
                <input type="text" class="box" required maxlength="100" placeholder="Enter Product Name" name="name">
            </div>

            <div class="inputBox">
                <span>Product Price (Required)</span>
                <input type="number" min="0" class="box" required max="9999999999" placeholder="Enter Product Price" onkeypress="if(this.value.length == 10) return false;" name="price">
            </div>

            <div class="inputBox">
                <span>Product Quantity (Required)</span>
                <input type="number" min="0" class="box" required placeholder="Enter Quantity" name="quantity">
            </div>

            <div class="inputBox">
                <span>Image 01 (required)</span>
                <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
            </div>

            <div class="inputBox">

                <span>Image 02 (required)</span>
                <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>

            </div>

            <div class="inputBox">
                <span>Image 03 (required)</span>
                <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
            </div>

            <div class="inputBox">
                <span>Product Details (required)</span>
                <textarea name="details" placeholder="Enter Product Details" class="box" required maxlength="500" cols="30" rows="10"></textarea>
            </div>

        </div>

        <input type="submit" value="Add Product" class="btn" name="add_product">


    

    </form>

</section>

<!-- SHOW products  -->


<section class="show-products">

    <h1 class="heading">products added</h1>

    <div class="box-container">

        <?php
        $select_products = $conn->prepare("SELECT * FROM `products`");
        $select_products->execute();
        if($select_products->rowCount() > 0)
        {
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
        ?>

        <div class="box">

            <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
            <div class="name"><?= $fetch_products['name']; ?></div>
            <div class="price">R<span><?= $fetch_products['price']; ?></span>/-</div>
            <div class="qty"> <span><?= $fetch_products['quantity']; ?></span> Available</div>
            <div class="details"><span><?= $fetch_products['details']; ?></span></div>

            <div class="flex-btn">

                <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
                <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>

            </div>
        </div>

        <?php
            }
        }
        else
        {
            echo '<p class="empty">no products added yet!</p>';
        }
        ?>

    </div>






</section>






<!-- js file link -->
 <script src="../js/admin_script.js"></script>

</body>
</html>