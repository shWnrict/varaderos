<?php require_once('config.php'); ?>
<?php require_once('inc/header.php') ?>
<?php require_once('inc/topBarNav.php') ?>

<style>
    .cancel-btn-container,
    .status-action-btns {
        display: none;
    }
</style>

<section class="py-2">
    <div class="container">
        <div class="card rounded-0">
            <div class="card-body">
                <div class="w-100 justify-content-between d-flex">
                    <h4><b>Orders</b></h4>
                    <a href="./edit_account.php" class="btn btn btn-dark btn-flat"><div class="fa fa-user-cog"></div> Manage Account</a>
                </div>
                    <hr class="border-warning">
                    <table class="table table-stripped text-dark">
                        <colgroup>
                            <col width="10%">
                            <col width="15">
                            <col width="25">
                            <col width="25">
                            <col width="15">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>DateTime</th>
                                <th>Transaction ID</th>
                                <th>Amount</th>
                                <th>Order Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $i = 1;
                                $qry = $conn->query("SELECT o.*,concat(c.firstname,' ',c.lastname) as client from `orders` o inner join clients c on c.id = o.client_id where o.client_id = '".$_settings->userdata('id')."' order by unix_timestamp(o.date_created) desc ");
                                while($row = $qry->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?php echo $i++ ?></td>
                                    <td><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
                                    <td><a href="javascript:void(0)" class="view_order" data-id="<?php echo $row['id'] ?>"><?php echo md5($row['id']); ?></a></td>
                                    <td><?php echo number_format($row['amount']) ?> </td>
                                    <td class="text-center status-cell">
                                        <span class="status-text">
                                            <?php if($row['status'] == 0): ?>
                                                <span class="status-pending">Pending</span>
                                            <?php elseif($row['status'] == 1): ?>
                                                <span class="badge badge-primary">Packed</span>
                                            <?php elseif($row['status'] == 2): ?>
                                                <span class="badge badge-warning">Out for Delivery</span>
                                            <?php elseif($row['status'] == 3): ?>
                                                <span class="badge badge-success">Delivered</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Cancelled</span>
                                            <?php endif; ?>
                                        </span>
                                        <?php if($row['status'] == 0 || $row['status'] == 2): ?>
                                            <div class="status-action-btns">
                                                <form class="status-update-form">
                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                    <input type="hidden" name="status" value="<?php echo $row['status'] == 0 ? '4' : '3'; ?>">
                                                    <button type="submit" class="btn <?php echo $row['status'] == 0 ? 'btn-danger' : 'btn-success'; ?> btn-sm">
                                                        <?php echo $row['status'] == 0 ? 'Cancel' : 'Confirm Receipt'; ?>
                                                    </button>
                                                </form>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    </tr>
                            <?php endwhile; ?>
                        </tbody>
                        
                    </table>
            </div>
        </div>
    </div>
</section>
<!-- added modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="orderDetailsModalBody">
                <!-- Content loaded dynamically using AJAX -->
            </div>
        </div>
    </div>
</div>

<section class="py-2">
    <div class="container">
        <!-- Existing table for current orders -->
        <div class="card rounded-0">
            <div class="card-body">
                <!-- ... Your existing table code ... -->
            </div>
        </div>
    </div>
</section>

<section class="py-2">
    <div class="container">
        <!-- New table for completed orders -->
        <div class="card rounded-0">
            <div class="card-body">
                <div class="w-100 justify-content-between d-flex">
                    <h4><b>Completed Orders</b></h4>
                </div>
                <hr class="border-warning">
                <table class="table table-stripped text-dark">
                    <colgroup>
                        <!-- Define your column widths here as needed -->
                        <col width="10%">
                        <col width="15">
                        <col width="25">
                        <col width="25">
                        <col width="15">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>DateTime</th>
                            <th>Transaction ID</th>
                            <th>Amount</th>
                            <th>Order Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        $qry_completed = $conn->query("SELECT o.*,concat(c.firstname,' ',c.lastname) as client FROM `completed_orders` o INNER JOIN clients c ON c.id = o.client_id WHERE o.client_id = '".$_settings->userdata('id')."' ORDER BY unix_timestamp(o.date_completed) DESC ");
                        while($row_completed = $qry_completed->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo $i++ ?></td>
                            <td><?php echo date("Y-m-d H:i", strtotime($row_completed['date_completed'])) ?></td>
                            <td><a href="javascript:void(0)" class="view_order_completed" data-id="<?php echo $row_completed['id'] ?>"><?php echo md5($row_completed['id']); ?></a></td>
                            <td><?php echo number_format($row_completed['amount']) ?></td>
                            <td class="text-center">
                                <?php
                                if ($row_completed['status'] == 3) {
                                    echo '<span class="badge badge-success">Completed</span>';
                                } else {
                                    echo '<span class="badge badge-danger">Cancelled</span>';
                                }
                                ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!-- added modal for completed orders-->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="orderDetailsModalBody">
                <!-- Content loaded dynamically using AJAX -->
            </div>
        </div>
    </div>
</div>

<?php require_once('inc/footer.php') ?>    
<script>
    function cancel_book($id){
        start_loader()
        $.ajax({
            url:_base_url_+"classes/Master.php?f=update_book_status",
            method:"POST",
            data:{id:$id,status:2},
            dataType:"json",
            error:err=>{
                console.log(err)
                alert_toast("an error occured",'error')
                end_loader()
            },
            success:function(resp){
                if(typeof resp == 'object' && resp.status == 'success'){
                    alert_toast("Book cancelled successfully",'success')
                    setTimeout(function(){
                        location.reload()
                    },2000)
                }else{
                    console.log(resp)
                    alert_toast("an error occured",'error')
                }
                end_loader()
            }
        })
    }

    // revised function
    $(function(){
    $('.view_order').click(function(){
        var orderId = $(this).attr('data-id');
        $.ajax({
            url: "./admin/orders/view_order.php?view=user&id=" + orderId,
            method: "GET",
            dataType: "html",
            success: function (resp) {
                $("#orderDetailsModalBody").html(resp);
                $("#orderDetailsModal").modal("show");
            },
            error: function (err) {
                console.log(err);
                alert("An error occurred while loading the order details.");
            }
        });
    });
    $('table').dataTable();

    // Handle click event for transaction ID in completed orders table
    $('.view_order_completed').click(function(){
        var orderId = $(this).attr('data-id');
        $.ajax({
            url: "./admin/completed/view_order.php?view=user&id=" + orderId,
            method: "GET",
            dataType: "html",
            success: function (resp) {
                $("#orderDetailsModalBody").html(resp);
                $("#orderDetailsModal").modal("show");
            },
            error: function (err) {
                console.log(err);
                alert("An error occurred while loading the order details.");
            }
        });
    });
});

$(document).ready(function () {
        // Show action buttons on hover
        $('.status-cell').hover(function () {
            $(this).find('.status-action-btns').fadeIn();
        }, function () {
            $(this).find('.status-action-btns').fadeOut();
        });

        // Handle the status update form submission
        $('.status-update-form').submit(function (e) {
            e.preventDefault();
            start_loader();
            var formData = $(this).serialize();
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=update_order_status",
                method: "POST",
                data: formData,
                dataType: "json",
                error: function (err) {
                    console.log(err);
                    alert_toast("An error occurred", "error");
                    end_loader();
                },
                success: function (resp) {
                    if (!!resp.status && resp.status == 'success') {
                        alert_toast("Order status successfully updated", "success");
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    } else {
                        console.log(resp);
                        alert_toast("An error occurred", "error");
                        end_loader();
                    }
                }
            });
        });
    });
    
</script>