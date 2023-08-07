<head>
  <!-- Other meta tags and stylesheets -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<style>
  .navbg {
    background-color: #D72C01;
    color: #ffff!important;
  }
  .nav-link{
    color: #fff!important;
  }
  #login-btn {
    color: #Fff;
  }

.navbar-toggler {
  color: #FFF!important;
  border: 1px solid #fff!important;
}
#search-form {
 border: 1px solid #fff;
 margin: 10px;
    width: 100%!important;
}
</style>
<nav class="navbar navbar-expand-lg text-light navbg">
    <div class="container px-4 px-lg-5 ">
        <button class="navbar-toggler btn btn-" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"> <i class="fas fa-bars"></i></span></button>
        <a class="navbar-brand" href="./">
            <img src="<?php echo validate_image($_settings->info('logo')) ?>" width="100" height="50" class="d-inline-block align-top" alt="" loading="lazy">
            <?php echo $_settings->info('short_name') ?>
        </a>

        <form class="form-inine" id="search-form">
            <div class="input-group">
                <input class="form-control form-control-sm form " type="search" placeholder="Search" aria-label="Search" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : "" ?>" aria-describedby="button-addon2">
                <div class="input-group-append">
                    <button class="btn btn-outline-light btn-sm m-0" type="submit" id="button-addon2"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item"><a class="nav-link text-light" aria-current="page" href="./HomePage.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="./about.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="./products.php">Products</a></li>
            </ul>
            <div class="d-flex align-items-center">
                <?php if (!isset($_SESSION['userdata']['id'])) : ?>
                    <button class="btn btn-outline-dark ml-2" id="login-btn" type="button">Login</button>
                <?php else : ?>
                    <a class="text-dark mr-2 nav-link" href="./cart.php">
                        <i class="bi-cart-fill me-1"></i>
                        Cart
                        <span class="badge bg-dark text-white ms-1 rounded-pill" id="cart-count">
                            <?php
                            if (isset($_SESSION['userdata']['id'])) :
                                $count = $conn->query("SELECT SUM(quantity) as items from `cart` where client_id =" . $_settings->userdata('id'))->fetch_assoc()['items'];
                                echo ($count > 0 ? $count : 0);
                            else :
                                echo "0";
                            endif;
                            ?>
                        </span>
                    </a>

                    <a href="./my_account.php" class="text-light  nav-link"><b> Hi, <?php echo $_settings->userdata('firstname') ?>!</b></a>
                    <a href="logout.php" class="text-light nav-link"><i class="fa fa-sign-out-alt"></i></a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Login Modal -->
        <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    
                </div>
            </div>
        </div>
    </div>
</nav>
<script>
    $(function () {
        $('#login-btn').click(function () {
                $('#loginModal .modal-content').load('login.php', function() {
                    $('#loginModal').modal('show');
                });
            });
        $('#navbarResponsive').on('show.bs.collapse', function () {
            $('#mainNav').addClass('navbar-shrink')
        })
        $('#navbarResponsive').on('hidden.bs.collapse', function () {
            if ($('body').offset.top == 0)
                $('#mainNav').removeClass('navbar-shrink')
        })
    })

    $('#search-form').submit(function (e) {
        e.preventDefault()
        var sTxt = $('[name="search"]').val()
        if (sTxt != '')
            location.href = './products.php?&search=' + sTxt;
    })
</script>
