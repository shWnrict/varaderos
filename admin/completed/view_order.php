<?php if (isset($_GET['view'])): ?>
    <?php require_once('../../config.php'); ?>
<?php endif; ?>

<?php if ($_settings->chk_flashdata('success')): ?>
    <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
    </script>
<?php endif; ?>

<?php
if (!isset($_GET['id'])) {
    $_settings->set_flashdata('error', 'No order ID Provided.');
    redirect('admin/?page=completed');
}

$order = $conn->query("SELECT o.*, concat(c.firstname,' ',c.lastname, ' – ', c.contact) as client FROM `completed_orders` o INNER JOIN clients c ON c.id = o.client_id WHERE o.id = '{$_GET['id']}'");

if ($order->num_rows > 0) {
    foreach ($order->fetch_assoc() as $k => $v) {
        $$k = $v;
    }
} else {
    $_settings->set_flashdata('error', 'Order ID provided is Unknown');
    redirect('admin/?page=completed');
}
?>

<div class="card card-outline card-primary">
    <div class="card-body">
        <div class="container-fluid">
            <p><b>Client Name: <?php echo $client ?></b></p>
            <p><b>Delivery Address: <?php echo $delivery_address ?></b></p>
            <table class="table-striped table table-bordered">
                <colgroup>
                    <col width="15%">
                    <col width="30%">
                    <col width="20%">
                    <col width="20%">
                </colgroup>
                <thead>
                <tr>
                    <th>QTY</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $olist = $conn->query("SELECT o.*, p.product_name FROM `completed_order_list` o INNER JOIN products p ON o.product_id = p.id WHERE o.order_id = '{$id}' ");
                while ($row = $olist->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo $row['quantity'] ?></td>
                        <td><?php echo $row['product_name'] . " ({$row['size']}) " ?></td>
                        <td class="text-right"><?php echo number_format($row['price']) ?></td>
                        <td class="text-right"><?php echo number_format($row['total']) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-6">
                <p>Payment Method: <?php echo $payment_method ?></p>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <a class="btn btn-flat btn-default" href="?page=completed">Close</a>
</div>
