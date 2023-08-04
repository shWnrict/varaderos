<?php require_once('config.php'); ?>
<?php
$title = "Title";
$sub_title = "Subtitle";

// Check if search parameter is set in the URL
if (isset($_GET['search'])) {
    $title = "Search Result for '" . $_GET['search'] . "'";
}

$whereData = "";

// Check if search parameter is set in the URL
if (isset($_GET['search'])) {
    $whereData = " and (product_name LIKE '%" . $_GET['search'] . "%' or description LIKE '%" . $_GET['search'] . "%')";
}
?>

<?php require_once('inc/header.php') ?>
<?php require_once('inc/topBarNav.php') ?>

<style>
  .btn-primarys {
    background-color: #D72C01 ;
    color: #fff;
    width: 100px;
  }
  .btn-primarys:hover {
    background-color: #fff;
    color: #D72C01;
    border: 1px solid #D72C01;
  }
</style>
<!-- Header-->
<header class="bg-dark py-5" id="main-header">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-white">
            <h1 class="display-4 fw-bolder"><?php echo $title ?></h1>
            <p class="lead fw-normal text-white-50 mb-0"><?php echo $sub_title ?></p>
        </div>
    </div>
</header>

<!-- Section-->
<section class="py-5">
    <div class="container-fluid px-4 px-lg-5 mt-5">

        <!-- Search result title -->
        <?php
        if (isset($_GET['search'])) {
            echo "<h4 class='text-center'><b>Search Result for '" . $_GET['search'] . "'</b></h4>";
        }
        ?>

        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">

            <?php
            $products = $conn->query("SELECT * FROM `products` where status = 1 {$whereData} order by rand() ");
            while ($row = $products->fetch_assoc()) :
                $upload_path = base_app . '/uploads/product_' . $row['id'];
                $img = "";
                if (is_dir($upload_path)) {
                    $fileO = scandir($upload_path);
                    if (isset($fileO[2]))
                        $img = "uploads/product_" . $row['id'] . "/" . $fileO[2];
                }
                $inventory = $conn->query("SELECT * FROM inventory where product_id = " . $row['id']);
                $inv = array();
                while ($ir = $inventory->fetch_assoc()) {
                    $inv[$ir['size']] = number_format($ir['price']);
                }
            ?>
                <!-- Product card -->
                <div class="col mb-5">
                    <div class="card h-100 product-item">
                        <!-- Product image-->
                        <img class="card-img-top w-100" src="<?php echo validate_image($img) ?>" loading="lazy" alt="..." />
                        <!-- Product details-->
                        <div class="card-body p-4">
                            <div class="text-center">
                                <!-- Product name-->
                                <h5 class="fw-bolder"><?php echo $row['product_name'] ?></h5>
                                <!-- Product price-->
                                <?php foreach ($inv as $k => $v) : ?>
                                    <span><b><?php echo $k ?>: </b><?php echo $v ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <!-- Product actions-->
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center">
                                <a class="btn btn-primarys " href="view_product.php?id=<?php echo md5($row['id']) ?>">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>

            <!-- No product listed message -->
            <?php
            if ($products->num_rows <= 0) {
                echo "<h4 class='text-center'><b>No Product Listed.</b></h4>";
            }
            ?>
        </div>
    </div>
</section>

<?php require_once('inc/footer.php') ?>
