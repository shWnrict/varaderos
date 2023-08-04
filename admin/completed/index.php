<?php if ($_settings->chk_flashdata('success')): ?>
    <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
    </script>
<?php endif; ?>
<?php if ($_settings->chk_flashdata('error')): ?>
    <script>
        alert_toast("<?php echo $_settings->flashdata('error') ?>", 'error')
    </script>
<?php endif; ?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">List of Completed Orders</h3>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="container-fluid">
                <table class="table table-bordered table-stripped">
                    <colgroup>
                        <col width="5%">
                        <col width="15%">
                        <col width="25%">
                        <col width="20%">
                        <col width="15%">
                    </colgroup>
                    <thead>
                    <tr>
                        <th></th>
                        <th>Order Date</th>
                        <th>Client</th>
                        <th>Total Amount</th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                        $i = 1;
                        $total_amount = 0; // Initialize the variable to store the sum
                        $qry = $conn->query("SELECT o.*, concat(c.firstname, ' ', c.lastname) as client FROM `completed_orders` o INNER JOIN clients c ON c.id = o.client_id ORDER BY unix_timestamp(o.date_completed) DESC ");
                        while ($row = $qry->fetch_assoc()):
                            $total_amount += $row['amount']; // Add each total amount to the sum
                     ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo date("Y-m-d H:i", strtotime($row['date_completed'])) ?></td>
                            <td><?php echo $row['client'] ?></td>
                            <td class="text-right"><?php echo number_format($row['amount']) ?></td>

                            <td align="center">
                                <a class="btn btn-flat btn-default btn-sm"
                                   href="?page=completed/view_order&id=<?php echo $row['id'] ?>">View Details</a>
                            </td>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan='3' class="text-right">Total Earnings</th>
                            <th class="text-right"><?php echo number_format($total_amount) ?></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.delete_data').click(function () {
            _conf("Are you sure to delete this order permanently?", "delete_order", [$(this).attr('data-id')])
        });
        $('.pay_order').click(function () {
            _conf("Are you sure to mark this order as paid?", "pay_order", [$(this).attr('data-id')])
        });
        $('.table').dataTable();
    });

    function pay_order($id) {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=pay_order",
            method: "POST",
            data: {id: $id},
            dataType: "json",
            error: err => {
                console.log(err);
                alert_toast("An error occurred.", 'error');
                end_loader();
            },
            success: function (resp) {
                if (typeof resp === 'object' && resp.status === 'success') {
                    location.reload();
                } else {
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                }
            }
        })
    }

    function delete_order($id) {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_order",
            method: "POST",
            data: {id: $id},
            dataType: "json",
            error: err => {
                console.log(err);
                alert_toast("An error occurred.", 'error');
                end_loader();
            },
            success: function (resp) {
                if (typeof resp === 'object' && resp.status === 'success') {
                    location.reload();
                } else {
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                }
            }
        })
    }
</script>
