<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>



<header class="header">

    <section class="flex">  <!--flexible conatiner -->

        <!-- <a href="dashboard.php" class="logo">Admin <span>Panel</span></a> -->
        <a href="dashboard" class "logo">  <!--will take user to dashboard -->
            <img src="../images/admin-logo2.png" alt="logo" >
        </a> 

        <!--main navigation bar-->
        <nav class="navbar">
            <a href="dashboard.php">Home</a>
            <a href="products.php">Products</a>
            <a href="placed_orders.php">Orders</a>
            <a href="admin_accounts.php">Admins</a> 
            <a href="users_accounts.php">Users</a>
            <a href="messages.php">Messages</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <!--profile container -->
        <div class="profile">
            <?php
                $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
                $select_profile->execute([$admin_id]);
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <p><?= $fetch_profile['name']; ?></p><!--display name from database -->

            <a href="update_profile.php" class="btn">update profile</a> <!--link to page to update -->
            <div class="flex-btn">
                <a href="../admin/register_admin.php" class="option-btn">register</a>
                <a href="../admin/admin_login.php" class="option-btn">login</a>
            </div>
                <a href="../components/admin_logout.php" class="delete-btn" onclick="return confirm('Logout from the website?');">logout</a> 
        </div>

    </section>

</header>