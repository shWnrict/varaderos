<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<?php if($_settings->chk_flashdata('error')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('error') ?>",'error')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Orders</h3>
		<!-- <div class="card-tools">
			<a href="?page=order/manage_order" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Create New</a>
		</div> -->
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
					<col width="10%">
					<col width="10%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th></th>
						<th>Order Date</th>
						<th>Client</th>
						<th>Total Amount</th>
						<th>Paid</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>

				<tbody>
					<?php 
					$i = 1;
					$qry = $conn->query("SELECT o.*, concat(c.firstname, ' ', c.lastname) as client FROM `orders` o INNER JOIN clients c ON c.id = o.client_id ORDER BY unix_timestamp(o.date_created) DESC ");
					while($row = $qry->fetch_assoc()):
					?>
					<tr>
						<td class="text-center"><?php echo $i++; ?></td>
						<td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
						<td><?php echo $row['client'] ?></td>
						<td class="text-right"><?php echo number_format($row['amount']) ?></td>
						<td class="text-center">
							<?php if($row['paid'] == 0): ?>
								<span class="badge badge-light">No</span>
							<?php else: ?>
								<span class="badge badge-success">Yes</span>
							<?php endif; ?>
						</td>
						<td class="text-center">
						<?php 
						switch($row['status']){
							case 0:
								echo '<span class="badge badge-light">Pending</span>';
								break;
							case 1:
								echo '<span class="badge badge-primary">Packed</span>';
								break;
							case 2:
								echo '<span class="badge badge-warning">Out for Delivery</span>';
								break;
							case 3:
								echo '<span class="badge badge-success">Delivered</span>';
								break;
							case 4:
								if($row['paid'] == 0){
									echo '<span class="badge badge-danger">Cancelled (Unpaid)</span>';
								} else {
									echo '<span class="badge badge-danger">Cancelled (Paid)</span>';
								}
								break;
							default:
								echo '<span class="badge badge-danger">Invalid Status</span>';
								break;
						}
						?>

						</td>

						<td align="center">
							<div class="dropdown">
								<button class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
									Action
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<div class="dropdown-menu" role="menu">
									<a class="dropdown-item" href="?page=orders/view_order&id=<?php echo $row['id'] ?>">View Order</a>
									<?php if ($row['paid'] == 0 && $row['status'] != 4): ?>
										<a class="dropdown-item pay_order" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Mark as Paid</a>
									<?php endif; ?>
									<?php if ($row['status'] == 3 && $row['paid'] == 1 || $row['status'] == 4): ?>
										<a class="dropdown-item finish_order" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Finish Order</a>
									<?php endif; ?>
									<?php if ($row['status'] != 4): ?>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
									<?php endif; ?>
								</div>
							</div>
						</td>



					</tr>
					<?php endwhile; ?>
				</tbody>

			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this order permanently?","delete_order",[$(this).attr('data-id')])
		})
		$('.pay_order').click(function(){
			_conf("Are you sure to mark this order as paid?","pay_order",[$(this).attr('data-id')])
		})
		$('.finish_order').click(function () {
			_conf("Are you sure to finish this order?", "finish_order", [$(this).attr('data-id')]);
		});
		$('.table').dataTable();

	})
	
	function finish_order($id) {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=finish_order",
            method: "POST",
            data: { id: $id },
            dataType: "json",
            error: err => {
                console.log(err);
                alert_toast("An error occurred.", 'error');
                end_loader();
            },
            success: function(resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.reload();
                } else {
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                }
            }
        })
	}

	function pay_order($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=pay_order",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
	function delete_order($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_order",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>