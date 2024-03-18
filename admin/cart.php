<?php
include('php-includes/connect.php');
include('php-includes/check-login.php');
$userid = $_SESSION['userid'];
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="shopp2.css">

    <title>Mlml Website  - Cart</title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    
    <!-- icon link -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>


<style>
    .user-bg .overlay-box {
    opacity: 0.9;
    position: absolute;
    top: 0px;
    left: 0px;
    right: 0px;
    height: 100%;
    text-align: center;
    background: rgb(97, 100, 193);
}
.user-bg .overlay-box .user-content {
    margin-top: 30px;
    padding: 15px;
}
a {
    color: rgb(51, 122, 183);
    text-decoration: none;
}
.text-white {
    color: rgb(255, 255, 255);
}
img {
    vertical-align: middle;
}
.user-bg {
    height: 260px;
    position: relative;
    margin: -15px;
    overflow: hidden;
}
.white-box {
    margin-bottom: 20px;
    background: rgb(255, 255, 255);
    padding: 15px;
}
.user-btm-box {
    padding: 40px 0 10px;
    clear: both;
    overflow: hidden;
}


</style> 

</head>

<body>


    <div id="wrapper">

        <!-- Navigation -->
        
        <?php include('php-includes/menu.php'); ?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Cart</h1>  
                        <div class="box"> 
                <div class="cart-count">0</div>
                <ion-icon name="cart"
                id="cart-icon" style=  "color:#036ba3;height:30px; width: 70px; margin-top:-50px;" ></ion-icon>
            </div>
                        <div class="nav">
                 
            <div class="cart">
      <div class="cart-title">Cart Items</div>
                <div class="cart-content">
                 
<!--
                    <div class="cart-box">
                        <img src="f1.jpg" class="cart-img">
                        <div class="detail-box">
                            <div class="cart-food-title">Grapes</div>
                            <div class="price-box">
                                 <div class="cart-price">Rs.72<br></div>
                                   <div class="cart-amt">Rs.72</div>
                            </div>
                            
                        </div>

                        <input type="number" value="1" class="cart-quantity">
                        <ion-icon name="trash" class="cart-remove"></ion-icon>
                    -->
                    </div>
                 

                
              
                <div class="total">
                    <div class="total-title"> Total</div>
                    <div class="total-price">Rs.0</div>
                </div>
               
               
                <a href="productrequest.php" class="btn-buy"> Place Order</a>
                <ion-icon name="close" id="cart-close"></ion-icon>
            </div>
        </div>
    
       <div class="shop-content">
        <div class="food-box">
            <div class="pic">
                <img src="./images/d6.png"  class="food-img">
            </div>
            <p class="food-title">01</p>
            <h3 style= color:red;>T-Shirt</h3>
            <strike> MRP:700</strike></br>
            <span class="food-price"> Rs.300  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>
        

        <div class="food-box">
            <div class="pic">
                <img src="./images/d8.png" class="food-img"> 

            </div>
               <p class="food-title">02</p>
            <h3 style= color:red;>6T-Shirt</br>Combo</h3>
            <strike> MRP:1498</strike></br>
            <span class="food-price"> Rs.1000  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>

        <div class="food-box">
            <div class="pic">
                <img src="./images/d9.png" class="food-img" >
            </div>
                <p class="food-title">03</p>
            <h3 style= color:red;>5Formal-Shirt</br>Combo</h3>
            <strike> MRP:1500</strike></br>
            <span class="food-price"> Rs.1200  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>
        <div class="food-box">
            <div class="pic">
                <img src="./images/d10.png" class="food-img">
            </div>
           <p class="food-title">04</p>
            <h3 style= color:red;>4Cotton-Shirt</br>Combo</h3>
            <strike> MRP:1200</strike></br>
            <span class="food-price"> Rs.999  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>

        <div class="food-box">
            <div class="pic">
                <img src="./images/d11.png" class="food-img">
            </div>
             <p class="food-title">05</p>
            <h3 style= color:red;>12 T-Shirt</br>Combo</h3>
            <strike> MRP:1700</strike></br>
            <span class="food-price"> Rs.1299  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>

        <div class="food-box">
            <div class="pic">
                <img src="./images/d12.png" class="food-img">
            </div>
            <p class="food-title">06</p>
            <h3 style= color:red;>Brand T-Shirt</br>Combo</h3>
            <strike> MRP:2999</strike></br>
            <span class="food-price"> Rs.1999  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>
        <div class="food-box">
            <div class="pic">
                <img src="./images/d13.png" class="food-img">
            </div>
           <p class="food-title">07</p>
            <h3 style= color:red;>T-Shirt & Shorts</br>Combo</h3>
            <strike> MRP:700</strike></br>
            <span class="food-price"> Rs.599  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>

        <div class="food-box">
            <div class="pic">
                <img src="./images/d4.png" class="food-img">
            </div>
           <p class="food-title">08</p>
            <h3 style= color:red;>FullHand-Shirt</h3>
            <strike> MRP:699</strike></br>
            <span class="food-price"> Rs.499  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
            
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>
         <div class="food-box">
            <div class="pic">
                <img src="./images/d2.png" class="food-img">
            </div>
            <p class="food-title">09</p>
            <h3 style= color:red;>T-Shirt</h3>
            <strike> MRP:599</strike></br>
            <span class="food-price"> Rs.399  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>
              <div class="food-box">
            <div class="pic">
                <img src="./images/d1.png" class="food-img">
            </div>
           <p class="food-title">10</p>
            <h3 style= color:red;>Checked-Shirt</h3>
            <strike> MRP:499</strike></br>
            <span class="food-price"> Rs.299  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>
        <div class="food-box">
            <div class="pic">
                <img src="./images/d3.png" class="food-img">
            </div>
             <p class="food-title">11</p>
            <h3 style= color:red;>Ash color-Shirt</h3>
            <strike> MRP:499</strike></br>
            <span class="food-price"> Rs.299  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>
        <div class="food-box">
            <div class="pic">
                <img src="./images/d4.png" class="food-img">
            </div>
           <p class="food-title">12</p>
            <h3 style= color:red;>Blue-Shirt<o</h3>
            <strike> MRP:499</strike></br>
            <span class="food-price"> Rs.299  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>
        <div class="food-box">
            <div class="pic">
                <img src="./images/d5.png" class="food-img">
            </div>
             <p class="food-title">13</p>
            <h3 style= color:red;>white-Shirt</h3>
            <strike> MRP:499</strike></br>
            <span class="food-price"> Rs.199  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>
        <div class="food-box">
            <div class="pic">
                <img src="./images/d6.png" class="food-img">
            </div>
             <p class="food-title">14</p>
            <h3 style= color:red;>SkyBlue-Shirt</h3>
            <strike> MRP:499</strike></br>
            <span class="food-price"> Rs.299  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>
        <div class="food-box">
            <div class="pic">
                <img src="./images/ts1.png" class="food-img">
            </div>
             <p class="food-title">15</p>
            <h3 style= color:red;>Green-Shirt</h3>
            <strike> MRP:499</strike></br>
            <span class="food-price"> Rs.299  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>
        <div class="food-box">
            <div class="pic">
                <img src="./images/ts2.png" class="food-img">
            </div>
             <p class="food-title">16</p>
            <h3 style= color:red;>Sandal-Shirt</h3>
            <strike> MRP:499</strike></br>
            <span class="food-price"> Rs.299  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>

        <div class="food-box">
            <div class="pic">
                <img src="./images/s6.png" class="food-img">
            </div>
            <p class="food-title">17</p>
            <h3 style= color:red;>Saree</h3>
            <strike> MRP:699</strike></br>
            <span class="food-price"> Rs.499  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>
        <div class="food-box">
            <div class="pic">
                <img src="./images/s5.png" class="food-img">
            </div>
            <p class="food-title">18</p>
            <h3 style= color:red;>Blue Saree</h3>
            <strike> MRP:699</strike></br>
            <span class="food-price"> Rs.499  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>
        <div class="food-box">
            <div class="pic">
                <img src="./images/sa2.png" class="food-img">
            </div>
             <p class="food-title">19</p>
            <h3 style= color:red;>Yellow Saree</h3>
            <strike> MRP:799</strike></br>
            <span class="food-price"> Rs.599  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>
        <div class="food-box">
            <div class="pic">
                <img src="./images/sa1.png" class="food-img">
            </div>
             <p class="food-title">20</p>
            <h3 style= color:red;>Rose Saree</h3>
            <strike> MRP:787</strike></br>
            <span class="food-price"> Rs.459  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>

        <div class="food-box">
            <div class="pic">
                <img src="./images/sa3.png" class="food-img">
            </div>
         <p class="food-title">21</p>
            <h3 style= color:red;>Cotton Saree</h3>
            <strike> MRP:655</strike></br>
            <span class="food-price"> Rs.299  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>
        <div class="food-box">
            <div class="pic">
                <img src="./images/sa4.png" class="food-img">
            </div>
             <p class="food-title">22</p>
            <h3 style= color:red;>Black Saree</h3>
            <strike> MRP:500</strike></br>
            <span class="food-price"> Rs.399  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>

        <div class="food-box">
            <div class="pic">
                <img src="./images/sa5.png" class="food-img">
            </div>
             <p class="food-title">23</p>
            <h3 style= color:red;>Multi color Saree</h3>
            <strike> MRP:699</strike></br>
            <span class="food-price"> Rs.499  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>

        <div class="food-box">
            <div class="pic">
                <img src="./images/sa6.png" class="food-img">
            </div>
             <p class="food-title">24</p>
            <h3 style= color:red;>Red Color Saree</h3>
            <strike> MRP:700</strike></br>
            <span class="food-price"> Rs.599  offer price </span>
            <ion-icon name="cart" class="add-cart"></ion-icon>
        </div>



       </div>
    </div>




                    </div>




                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                
              



          </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

    <script src="shopping.js"></script>

</body>

</html>
