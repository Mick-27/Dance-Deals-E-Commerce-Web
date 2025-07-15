<?php

include '../components/connect.php';

session_start(); //start session

$seller_id = $_SESSION['seller_id']; //get session id from session and stores in session_id

if(!isset($seller_id)){ //if admin not set
    header('location:seller_login.php'); //back to log in page 
}





if(isset($_POST['update'])){

    $pid = $_POST['pid'];
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $quantity = $_POST['quantity'];
    $quantity = filter_var($quantity, FILTER_SANITIZE_NUMBER_INT);
    $details = $_POST['details'];
    $details = filter_var($details, FILTER_SANITIZE_STRING);
 
    $update_product = $conn->prepare("UPDATE `sellers_products` SET name = ?, price = ?, quantity = ?, details = ? WHERE id = ?");
    $update_product->execute([$name, $price, $quantity, $details, $pid]);
 
    $message[] = 'product updated successfully!';
 
    $old_image_01 = $_POST['old_image_01'];
    $image_01 = $_FILES['image_01']['name'];
    $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
    $image_size_01 = $_FILES['image_01']['size'];
    $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
    $image_folder_01 = '../uploaded_img/'.$image_01;
 
    if(!empty($image_01)){
       if($image_size_01 > 2000000){
          $message[] = 'image size is too large!';
       }else{
          $update_image_01 = $conn->prepare("UPDATE `sellers_products` SET image_01 = ? WHERE id = ?");
          $update_image_01->execute([$image_01, $pid]);
          move_uploaded_file($image_tmp_name_01, $image_folder_01);
          unlink('../uploaded_img/'.$old_image_01);
          $message[] = 'image 01 updated successfully!';
       }
    }
 
    $old_image_02 = $_POST['old_image_02'];
    $image_02 = $_FILES['image_02']['name'];
    $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
    $image_size_02 = $_FILES['image_02']['size'];
    $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
    $image_folder_02 = '../uploaded_img/'.$image_02;
 
    if(!empty($image_02)){
       if($image_size_02 > 2000000){
          $message[] = 'image size is too large!';
       }else{
          $update_image_02 = $conn->prepare("UPDATE `sellers_products` SET image_02 = ? WHERE id = ?");
          $update_image_02->execute([$image_02, $pid]);
          move_uploaded_file($image_tmp_name_02, $image_folder_02);
          unlink('../uploaded_img/'.$old_image_02);
          $message[] = 'image 02 updated successfully!';
       }
    }
 
    $old_image_03 = $_POST['old_image_03'];
    $image_03 = $_FILES['image_03']['name'];
    $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
    $image_size_03 = $_FILES['image_03']['size'];
    $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
    $image_folder_03 = '../uploaded_img/'.$image_03;
 
    if(!empty($image_03)){
       if($image_size_03 > 2000000){
          $message[] = 'image size is too large!';
       }else{
          $update_image_03 = $conn->prepare("UPDATE `sellers_products` SET image_03 = ? WHERE id = ?");
          $update_image_03->execute([$image_03, $pid]);
          move_uploaded_file($image_tmp_name_03, $image_folder_03);
          unlink('../uploaded_img/'.$old_image_03);
          $message[] = 'image 03 updated successfully!';
       }
    }
 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Update</title>

    <!-- font awesome link  -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

     <!-- custom css link -->
      <link rel="stylesheet" href="../css/admin_style.css">

</head>


<body>
    
    <?php include '../components/seller_header.php'; ?>

    <!-- update product section  -->

    <section class="update-product">

        <h1 class="heading">update product</h1>

        <?php
            $update_id = $_GET['update'];
            $select_products = $conn->prepare("SELECT * FROM `sellers_products` WHERE id = ?");
            $select_products->execute([$update_id]);
            if($select_products->rowCount() > 0)
            {
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
        ?>

            <form action="" method="post" enctype="multipart/form-data">

                <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                <input type="hidden" name="old_image_01" value="<?= $fetch_products['image_01']; ?>">
                <input type="hidden" name="old_image_02" value="<?= $fetch_products['image_02']; ?>">
                <input type="hidden" name="old_image_03" value="<?= $fetch_products['image_03']; ?>">

                <div class="image-container">

                    <div class="main-image">
                        <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
                    </div>

                    <div class="sub-image">
                        <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
                        <img src="../uploaded_img/<?= $fetch_products['image_02']; ?>" alt="">
                        <img src="../uploaded_img/<?= $fetch_products['image_03']; ?>" alt="">
                    </div>

                </div>


                <span>Update Name</span>
                <input type="text" name="name" required class="box" maxlength="100" placeholder="enter product name" value="<?= $fetch_products['name']; ?>">
                
                <span>Update Price</span>
                <input type="number" name="price" required class="box" min="0" max="9999999999" placeholder="enter product price" onkeypress="if(this.value.length == 10) return false;" value="<?= $fetch_products['price']; ?>">
                
                 <span>Update Quantity</span>
                <input type="number" min="0" class="box" required placeholder="Enter Quantity" name="quantity">

                <span>Update Details</span>
                <textarea name="details" class="box" required cols="30" rows="10"><?= $fetch_products['details']; ?></textarea>
                
                <span>Update image 01</span>
                <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
                
                <span>Update image 02</span>
                <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
                
                <span>Update image 03</span>
                <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">

                <div class="flex-btn">
                    <input type="submit" name="update" class="btn" value="update">
                    <a href="products.php" class="option-btn">go back</a>
                </div>

            </form>

            <?php
                }
            }else{
                echo '<p class="empty">no product found!</p>';
            }
        ?>

    </section>

    



    <!-- js file link -->
    <script src="../js/admin_script.js"></script>

</body>
</html>