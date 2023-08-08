<?php 
require_once('config.php');
$products = $conn->query("SELECT * FROM `products` WHERE md5(id) = '{$_GET['id']}' ");
if ($products->num_rows > 0) {
    foreach ($products->fetch_assoc() as $k => $v) {
        $$k = $v;
    }
    $upload_path = base_app . '/uploads/product_' . $id;
    $img = "";
    if (is_dir($upload_path)) {
        $fileO = scandir($upload_path);
        if (isset($fileO[2]))
            $img = "uploads/product_" . $id . "/" . $fileO[2];
        // var_dump($fileO);
    }
    $inventory = $conn->query("SELECT * FROM inventory WHERE product_id = " . $id);
    $inv = array();
    while ($ir = $inventory->fetch_assoc()) {
        $inv[] = $ir;
    }
}
?>
<?php require_once('inc/header.php') ?>
<?php require_once('inc/topBarNav.php') ?>

<section class="py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5 align-items-center">
            <div class="col-md-6">
                <img class="card-img-top mb-5 mb-md-0" loading="lazy" id="display-img" src="<?php echo validate_image($img) ?>" alt="..." />
                <div class="mt-2 row gx-2 gx-lg-3 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-start">
                    <?php 
                    foreach ($fileO as $k => $img):
                        if (in_array($img, array('.', '..')))
                            continue;
                    ?>
                        <a href="javascript:void(0)" class="view-image <?php echo $k == 2 ? "active" : '' ?>"><img src="<?php echo validate_image('uploads/product_' . $id . '/' . $img) ?>" loading="lazy" class="img-thumbnail" alt=""></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-md-6">
                <h1 class="display-5 fw-bolder"><?php echo $product_name ?></h1>
                <!-- <div class="fs-5 mb-5">
                    &#8369; <span id="price"><?php echo $inv[0]['price'] ?></span>
                    <br>
                    <span><small><b>Available stock:</b> <span id="avail"><?php echo $inv[0]['quantity'] ?></span></small></span>
                </div> -->
                <div class="fs-5 mb-5">
                    &#8369; <span id="price"><?php echo $inv[0]['price'] ?></span>
                    <br>
                    <!-- Display a message for out of stock products -->
                    <?php if ($inv[0]['quantity'] > 0): ?>
                        <span><small><b>Available stock:</b> <span id="avail"><?php echo $inv[0]['quantity'] ?></span></small></span>
                    <?php else: ?>
                        <span><small><b>Out of stock</b></small></span>
                    <?php endif; ?>
                </div>
                
                <div class="fs-5 mb-5 d-flex justify-content-start">
                    <?php foreach ($inv as $k => $v): ?>
                        <?php
                            // Check if the current product's quantity is greater than zero
                            $isAvailable = $v['quantity'] > 0;
                            // Add the 'disabled' attribute to the button if the product is out of stock
                            $disabledAttribute = $isAvailable ? '' : 'disabled';
                        ?>
                        <span>
                            <!-- Add the $disabledAttribute to the button -->
                            <button class="btn btn-sm btn-flat btn-outline-dark m-2 p-size <?php echo $k == 0 ? "active" : '' ?>" data-id="<?php echo $k ?>" <?php echo $disabledAttribute ?>><?php echo $v['size'] ?></button>
                        </span>
                    <?php endforeach; ?>
                </div>
                

                <form action="" id="add-cart">
                    <div class="d-flex">
                        <input type="hidden" name="price" value="<?php echo $inv[0]['price'] ?>">
                        <input type="hidden" name="inventory_id" value="<?php echo $inv[0]['id'] ?>">
                        <input class="form-control text-center me-3" id="inputQuantity" type="num" value="1" style="max-width: 3rem" name="quantity" />
                        <button class="btn btn-outline-dark flex-shrink-0" type="submit">
                            <i class="bi-cart-fill me-1"></i>
                            Add to cart
                        </button>
                    </div>
                </form>
                <p class="lead"><?php echo stripslashes(html_entity_decode($description)) ?></p>
                
            </div>
        </div>
    </div>
</section>
<?php require_once('inc/footer.php') ?>

<script>
    var inv = $.parseJSON('<?php echo json_encode($inv) ?>');
    $(function(){
        $('.view-image').click(function(){
            var _img = $(this).find('img').attr('src');
            $('#display-img').attr('src',_img);
            $('.view-image').removeClass("active")
            $(this).addClass("active")
        })
        $('.p-size').click(function(){
            var k = $(this).attr('data-id');
            $('.p-size').removeClass("active")
            $(this).addClass("active")
            $('#price').text(inv[k].price)
            $('[name="price"]').val(inv[k].price)
            $('#avail').text(inv[k].quantity)
            $('[name="inventory_id"]').val(inv[k].id)

        })

        $('#add-cart').submit(function(e){
            e.preventDefault();
            if('<?php echo $_settings->userdata('id') ?>' <= 0){
                uni_modal("","login.php");
                return false;
            }
            start_loader();
            $.ajax({
                url:'classes/Master.php?f=add_to_cart',
                data:$(this).serialize(),
                method:'POST',
                dataType:"json",
                error:err=>{
                    console.log(err)
                    alert_toast("an error occured",'error')
                    end_loader()
                },
                success:function(resp){
                    if(typeof resp == 'object' && resp.status=='success'){
                        alert_toast("Product added to cart.",'success')
                        $('#cart-count').text(resp.cart_count)
                    }else{
                        console.log(resp)
                        alert_toast("an error occured",'error')
                    }
                    end_loader();
                }
            })
        })
    })
</script>
