<div class="menu-wrap">
    <!-- Mobile Menu Button -->
    <div id="mobnav-btn" class="mobile-menu-btn">Menu <i class="fa fa-bars"></i></div>

    <!-- Side Menu -->
    <div id="side-menu" class="side-menu">
        <div class="close-btn"></div>
        <ul class="sf-menu">
            <li><a href="index.php">Home</a></li>
            <li>
                <a href="#">Category</a>
                <div class="mobnav-subarrow"><i class="fa fa-plus"></i></div>
                <ul>
                <?php
                    $catsql = "SELECT * FROM category";
                    $catres = mysqli_query($connection, $catsql);
                    while($catr = mysqli_fetch_assoc($catres)){
                 ?>
                    <li><a href="index.php?id=<?php echo $catr['id']; ?>"><?php echo $catr['name']; ?></a></li>
                <?php } ?>
                </ul>
            </li>
            <li>
                <a href="#">My Account</a>
                <div class="mobnav-subarrow"><i class="fa fa-plus"></i></div>
                <ul>
                    <li><a href="my-account.php">My Orders</a></li>
                    <li><a href="edit-address.php">Update Address</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
        <div class="header-xtra">
        <?php 
         if(isset($_SESSION['cart'])){
        $cart = $_SESSION['cart']; 
        ?>
            <div class="s-cart">
                <div class="sc-ico"><i class="fa fa-shopping-cart"></i><em><?php echo count($cart); ?></em></div>
                <div class="cart-info">
                    <small>You have <em class="highlight"><?php echo count($cart); ?> item(s)</em> in your shopping bag</small>
                    <br><br>
                    <?php
                        $total = 0;
                        foreach ($cart as $key => $value) {
                            $navcartsql = "SELECT * FROM products WHERE id=$key";
                            $navcartres = mysqli_query($connection, $navcartsql);
                            $navcartr = mysqli_fetch_assoc($navcartres);
                    ?>
                    <div class="ci-item">
                        <img src="admin/<?php echo $navcartr['thumb']; ?>" width="70" alt=""/>
                        <div class="ci-item-info">
                            <h5><a href="single.php?id=<?php echo $navcartr['id']; ?>"><?php echo substr($navcartr['name'], 0 , 20); ?></a></h5>
                            <p><?php echo $value['quantity']; ?> x INR <?php echo $navcartr['price']; ?>.00/-</p>
                            <div class="ci-edit">
                                <a href="delcart.php?id=<?php echo $key; ?>" class="edit fa fa-trash"></a>
                            </div>
                        </div>
                    </div>
                    <?php 
                    $total = $total + ($navcartr['price']*$value['quantity']);
                    } ?>
                    <div class="ci-total">Subtotal:Birr <?php echo $total; ?>.00/-</div>
                    <div class="cart-btn">
                        <a href="cart.php">View Bag</a>
                        <a href="checkout.php">Checkout</a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    
</div>

<!-- Inline CSS for responsive menu -->
<style>
/* Default styles for desktop */
.mobile-menu-btn {
    display: none;
}

.side-menu.open {
    transform: translateX(0);
}

.side-menu .close-btn {
    position: absolute;
    top: 0px;
    right: 20px;
    font-size: 24px;
    color: #fff;
    cursor: pointer;
    z-index: 1000; /* Ensure it's above other content */
}

.side-menu .close-btn::before {
    content: "×"; /* Unicode for the multiplication sign */
    display: block;
}

.sf-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sf-menu li {
    padding: 15px;
}

.sf-menu li a {
    color: #fff;
    text-decoration: none;
}

.sf-menu li ul {
    display: none;
}

.sf-menu li:hover ul {
    display: block;
}

/* Mobile styles */
@media (max-width: 768px) {
    .mobile-menu-btn {
        display: block;
        cursor: pointer;
    }

    .sf-menu {
        display: none;
    }
}

.desktop-nav {
    display: block;
}

@media (max-width: 768px) {
    .desktop-nav {
        display: none;
    }
}
</style>

<!-- Inline JavaScript for menu functionality -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sideMenu = document.getElementById('side-menu');
    const closeBtn = document.querySelector('.close-btn');
    const menuBtn = document.getElementById('mobnav-btn');

    menuBtn.addEventListener('click', function () {
        sideMenu.classList.add('open');
    });

    closeBtn.addEventListener('click', function () {
        sideMenu.classList.remove('open');
    });
});

</script>
