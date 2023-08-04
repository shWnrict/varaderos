<?php require_once('config.php'); ?>
<?php require_once('inc/header.php') ?>
<?php require_once('inc/topBarNav.php') ?>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col d-flex justify-content-end mb-2">
           <!-- <button class="btn btn-outline-dark btn-flat btn-sm" type="button" id="empty_cart">Empty Cart</button> -->
                <button class="btn btn-outline-dark btn-flat btn-sm" type="button" onclick="showEmptyCartConfirmation()">Empty Cart</button>

        </div>
        </div>
        <div class="card rounded-0">
            <div class="card-body">
                <h3><b>Cart List</b></h3>
                <hr class="border-dark">
                <?php 
                    $qry = $conn->query("SELECT c.*,p.product_name,i.size,i.price,p.id as pid from `cart` c inner join `inventory` i on i.id=c.inventory_id inner join products p on p.id = i.product_id where c.client_id = ".$_settings->userdata('id'));
                    while($row= $qry->fetch_assoc()):
                        $upload_path = base_app.'/uploads/product_'.$row['pid'];
                        $img = "";
                        if(is_dir($upload_path)){
                            $fileO = scandir($upload_path);
                            if(isset($fileO[2]))
                                $img = "uploads/product_".$row['pid']."/".$fileO[2];
                            // var_dump($fileO);
                        }
                ?>
                    <div class="d-flex w-100 justify-content-between  mb-2 py-2 border-bottom cart-item">
                        <div class="d-flex align-items-center col-8">
                            <!-- <span class="mr-2"><a href="javascript:void(0)" class="btn btn-sm btn-outline-danger rem_item" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i></a></span> -->

                            <span class="mr-2">
                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger rem_item" data-id="<?php echo $row['id'] ?>" onclick="showConfirmationModal()">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </span>


                            <img src="<?php echo validate_image($img) ?>" loading="lazy" class="cart-prod-img mr-2 mr-sm-2 border" alt="">
                            <div>
                                <p class="mb-1 mb-sm-1"><?php echo $row['product_name'] ?></p>
                                <p class="mb-1 mb-sm-1"><small><b>Size:</b> <?php echo $row['size'] ?></small></p>
                                <p class="mb-1 mb-sm-1"><small><b>Price:</b> <span class="price"><?php echo number_format($row['price']) ?></span></small></p>
                                <div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-sm btn-outline-secondary min-qty" type="button" id="button-addon1"><i class="fa fa-minus"></i></button>
                                    </div>
                                    <input type="number" class="form-control form-control-sm qty text-center cart-qty" placeholder="" aria-label="Example text with button addon" value="<?php echo $row['quantity'] ?>" aria-describedby="button-addon1" data-id="<?php echo $row['id'] ?>" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-outline-secondary plus-qty" type="button" id="button-addon1"><i class="fa fa-plus"></i></button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col text-right align-items-center d-flex justify-content-end">
                            <h4><b class="total-amount"><?php echo number_format($row['price'] * $row['quantity']) ?></b></h4>
                        </div>
                    </div>
                <?php endwhile; ?>
                <div class="d-flex w-100 justify-content-between mb-2 py-2 border-bottom">
                    <div class="col-8 d-flex justify-content-end"><h4>Grand Total:</h4></div>
                    <div class="col d-flex justify-content-end"><h4 id="grand-total">-</h4></div>
                </div>
            </div>
        </div>
        <div class="d-flex w-100 justify-content-end">
            <!-- <a href="./?p=checkout" class="btn btn-sm btn-flat btn-dark">Checkout</a> -->
            <a href="./checkout.php" class="btn btn-sm btn-flat btn-dark">Checkout</a>    
        </div>
    </div>
</section>
<!-- Delete Cart Confirmation Modal -->
<div class="modal fade" id="confirm_modal" tabindex="-1" role="dialog" aria-labelledby="confirm_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirm_modalLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to proceed with this action?</p>
            </div>
            <div class="modal-footer">
                <!-- The "Continue" button -->
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="proceedUpdate()">Continue</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- Empty Cart Confirmation Modal -->
<div class="modal fade" id="empty_cart_modal" tabindex="-1" role="dialog" aria-labelledby="empty_cart_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="empty_cart_modalLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to empty your cart?</p>
            </div>
            <div class="modal-footer">
                <!-- The "Continue" button to empty the cart -->
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="empty_cart()">Continue</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<?php require_once('inc/footer.php') ?>
<script>
    function calc_total(){
        var total  = 0

        $('.total-amount').each(function(){
            amount = $(this).text()
            amount = amount.replace(/\,/g,'')
            amount = parseFloat(amount)
            total += amount
        })
        $('#grand-total').text(parseFloat(total).toLocaleString('en-US'))

    }

  function qty_change($type, _this) {
        var qty = parseInt(_this.closest('.cart-item').find('.cart-qty').val());
        var price = parseFloat(_this.closest('.cart-item').find('.price').text().replace(/\,/g, ''));
        var cart_id = _this.closest('.cart-item').find('.cart-qty').attr('data-id');
        var new_total = 0;

        start_loader();
        if ($type == 'minus') {
            qty = qty - 1;
            if (qty < 1) {
                end_loader();
                return;
            }
        } else {
            qty = qty + 1;
        }

        new_total = parseFloat(qty * price).toLocaleString('en-US');
        _this.closest('.cart-item').find('.cart-qty').val(qty);
        _this.closest('.cart-item').find('.total-amount').text(new_total);

        calc_total();

        $.ajax({
            url: 'classes/Master.php?f=update_cart_qty',
            method: 'POST',
            data: { id: cart_id, quantity: qty },
            dataType: 'json',
            error: err => {
                console.log(err);
                alert_toast("An error occurred", 'error');
                end_loader();
            },
            success: function (resp) {
                if (!!resp.status && resp.status == 'success') {
                    end_loader();
                } else {
                    alert_toast("An error occurred", 'error');
                    end_loader();
                }
            }

        });
    }
    function rem_item(id){
        $('.modal').modal('hide')
        var _this = $('.rem_item[data-id="'+id+'"]')
        var id = _this.attr('data-id')
        var item = _this.closest('.cart-item')
        start_loader();
        $.ajax({
            url:'classes/Master.php?f=delete_cart',
            method:'POST',
            data:{id:id},
            dataType:'json',
            error:err=>{
                console.log(err)
                alert_toast("an error occured", 'error');
                end_loader()
            },
            success:function(resp){
                if(!!resp.status && resp.status == 'success'){
                    item.hide('slow',function(){ item.remove() })
                    calc_total()
                    end_loader()
                }else{
                    alert_toast("an error occured", 'error');
                    end_loader()
                }
            }

        })
    }
    function empty_cart(){
        start_loader();
        $.ajax({
            url:'classes/Master.php?f=empty_cart',
            method:'POST',
            data:{},
            dataType:'json',
            error:err=>{
                console.log(err)
                alert_toast("an error occured", 'error');
                end_loader()
            },
            success:function(resp){
                if(!!resp.status && resp.status == 'success'){
                   location.reload()
                }else{
                    alert_toast("an error occured", 'error');
                    end_loader()
                }
            }

        })
    }
    function showConfirmationModal() {
        actionToConfirm = 'rem_item';
        $('#confirm_modal').modal('show');
    }
    function showEmptyCartConfirmation() {
        actionToConfirm = 'empty_cart';
        $('#confirm_modal').modal('show');
    }
    var actionToConfirm = '';
    function proceedUpdate() {
        var id = $('.rem_item[data-id]').attr('data-id');
        if (actionToConfirm == 'empty_cart') {
            empty_cart();
        } else {
            rem_item(id);
        }
        $('#confirm_modal').modal('hide');
    }
    $(function(){
        calc_total()
        $('.min-qty').click(function(){
            qty_change('minus',$(this))
        })
        $('.plus-qty').click(function(){
            qty_change('plus',$(this))
        })
        $('#empty_cart').click(function(){
            // empty_cart()
            _conf("Are you sure to empty your cart list?",'empty_cart',[])
        })
        $('.rem_item').click(function(){
            _conf("Are you sure to remove the item in cart list?",'rem_item',[$(this).attr('data-id')])
        })
    })
</script>