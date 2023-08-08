<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_product(){
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'description'))) {
				if (!empty($data)) $data .= ",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if (isset($_POST['description'])) {
			if (!empty($data)) $data .= ",";
			$data .= " `description`='" . addslashes(htmlentities($description)) . "' ";
		}
		$product_name = $this->conn->real_escape_string($product_name); // Escape the product_name to avoid SQL injection
		$check = $this->conn->query("SELECT * FROM `products` WHERE `product_name` = '{$product_name}' " . (!empty($id) ? "AND id != {$id} " : ""))->num_rows;
		if ($this->capture_err())
			return $this->capture_err();
		if ($check > 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "Product already exists.";
			return json_encode($resp);
			exit;
		}
		if (empty($id)) {
			$sql = "INSERT INTO `products` SET {$data} ";
			$save = $this->conn->query($sql);
			$id = $this->conn->insert_id;
		} else {
			$sql = "UPDATE `products` SET {$data} WHERE id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if ($save) {
			$upload_path = "uploads/product_" . $id;
			if (!is_dir(base_app . $upload_path))
				mkdir(base_app . $upload_path);
			if (isset($_FILES['img']) && count($_FILES['img']['tmp_name']) > 0) {
				foreach ($_FILES['img']['tmp_name'] as $k => $v) {
					if (!empty($_FILES['img']['tmp_name'][$k])) {
						move_uploaded_file($_FILES['img']['tmp_name'][$k], base_app . $upload_path . '/' . $_FILES['img']['name'][$k]);
					}
				}
			}
			$resp['status'] = 'success';
			if (empty($id))
				$this->settings->set_flashdata('success', "New Product successfully saved.");
			else
				$this->settings->set_flashdata('success', "Product successfully updated.");
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_product(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `products` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Product successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function delete_img(){
		extract($_POST);
		if(is_file($path)){
			if(unlink($path)){
				$resp['status'] = 'success';
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = 'failed to delete '.$path;
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = 'Unkown '.$path.' path';
		}
		return json_encode($resp);
	}
	function save_inventory(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id','description'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `inventory` where `product_id` = '{$product_id}' and `size` = '{$size}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Inventory already exist.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `inventory` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `inventory` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"New Inventory successfully saved.");
			else
				$this->settings->set_flashdata('success',"Inventory successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_inventory(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `inventory` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Inventory successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function register(){
		extract($_POST);
		$data = "";
		$_POST['password'] = md5($_POST['password']);
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `clients` where `email` = '{$email}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Email already taken.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `clients` set {$data} ";
			$save = $this->conn->query($sql);
			$id = $this->conn->insert_id;
		}else{
			$sql = "UPDATE `clients` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"Account successfully created.");
			else
				$this->settings->set_flashdata('success',"Account successfully updated.");
			foreach($_POST as $k =>$v){
					$this->settings->set_userdata($k,$v);
			}
			$this->settings->set_userdata('id',$id);

		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function add_to_cart(){
		extract($_POST);
		// Check if user is logged in
		if (!isset($_SESSION['userdata'])) {
			$resp['status'] = 'failed';
			$resp['err'] = 'Please login first.';
			return json_encode($resp);
		}
		
		$data = " client_id = '".$this->settings->userdata('id')."' ";
		$_POST['price'] = str_replace(",","",$_POST['price']); 
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		
		$check = $this->conn->query("SELECT * FROM `cart` where `inventory_id` = '{$inventory_id}' and client_id = ".$this->settings->userdata('id'))->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$sql = "UPDATE `cart` set quantity = quantity + {$quantity} where `inventory_id` = '{$inventory_id}' and client_id = ".$this->settings->userdata('id');
		}else{
			$sql = "INSERT INTO `cart` set {$data} ";
		}
		
		$save = $this->conn->query($sql);
		if($this->capture_err())
			return $this->capture_err();
			if($save){
				$resp['status'] = 'success';
				$resp['cart_count'] = $this->conn->query("SELECT SUM(quantity) as items from `cart` where client_id =".$this->settings->userdata('id'))->fetch_assoc()['items'];
			}else{
				$resp['status'] = 'failed';
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
			return json_encode($resp);
	}
	function update_cart_qty(){
		extract($_POST);
		
		$save = $this->conn->query("UPDATE `cart` set quantity = '{$quantity}' where id = '{$id}'");
		if($this->capture_err())
			return $this->capture_err();
		if($save){
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
		
	}
	function empty_cart(){
		$delete = $this->conn->query("DELETE FROM `cart` where client_id = ".$this->settings->userdata('id'));
		if($this->capture_err())
			return $this->capture_err();
		if($delete){
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_cart(){
		extract($_POST);
		$delete = $this->conn->query("DELETE FROM `cart` where id = '{$id}'");
		if($this->capture_err())
			return $this->capture_err();
		if($delete){
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_order(){
		extract($_POST);
		$delete = $this->conn->query("DELETE FROM `orders` where id = '{$id}'");
		$delete2 = $this->conn->query("DELETE FROM `order_list` where order_id = '{$id}'");
		$delete3 = $this->conn->query("DELETE FROM `sales` where order_id = '{$id}'");
		if($this->capture_err())
			return $this->capture_err();
		if($delete){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Order successfully deleted");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function place_order(){
		extract($_POST);
		$client_id = $this->settings->userdata('id');
		
		$data = " client_id = '{$client_id}' ";
		$data .= " ,payment_method = '{$payment_method}' ";
		$data .= " ,amount = '{$amount}' ";
		$data .= " ,paid = '{$paid}' ";
		$data .= " ,delivery_address = '{$delivery_address}' ";
		$order_sql = "INSERT INTO `orders` SET $data";
		$save_order = $this->conn->query($order_sql);
		if($this->capture_err())
			return $this->capture_err();
		if($save_order){
			$order_id = $this->conn->insert_id;
			$data = '';
			$cart = $this->conn->query("SELECT c.*,p.product_name,i.size,i.price,p.id as pid from `cart` c INNER JOIN `inventory` i ON i.id=c.inventory_id INNER JOIN products p ON p.id = i.product_id WHERE c.client_id ='{$client_id}' ");
			while($row = $cart->fetch_assoc()){
				if(!empty($data)) $data .= ", ";
				$total = $row['price'] * $row['quantity'];
				$data .= "('{$order_id}','{$row['pid']}','{$row['size']}','{$row['quantity']}','{$row['price']}', $total)";
			}
			$list_sql = "INSERT INTO `order_list` (order_id, product_id, size, quantity, price, total) VALUES {$data} ";
			$save_olist = $this->conn->query($list_sql);
			if($this->capture_err())
				return $this->capture_err();
			if($save_olist){
				$this->deduct_inventory($order_id);

				$empty_cart = $this->conn->query("DELETE FROM `cart` WHERE client_id = '{$client_id}'");
				$data = " order_id = '{$order_id}'";
				$data .= " ,total_amount = '{$amount}'";
				$save_sales = $this->conn->query("INSERT INTO `sales` SET $data");
				if($this->capture_err())
					return $this->capture_err();
				$resp['status'] = 'success';
			}else{
				$resp['status'] = 'failed';
				$resp['err_sql'] = $save_olist;
			}
	
		}else{
			$resp['status'] = 'failed';
			$resp['err_sql'] = $save_order;
		}
		return json_encode($resp);
	}
	function update_order_status(){
		extract($_POST);
		$order = $this->conn->query("SELECT status FROM `orders` WHERE id = '{$id}' ");
		if ($order->num_rows > 0) {
			$order_data = $order->fetch_assoc();
			$current_status = $order_data['status'];
		}
	
		$update = $this->conn->query("UPDATE `orders` SET `status` = '$status' WHERE id = '{$id}' ");
		if($update){
			if (($current_status == 1 || $current_status == 2 || $current_status == 0) && $status == 4) {
				$this->add_inventory($id);
			} elseif ($current_status == 4 && ($status == 1 || $status == 2)) {
				// Do nothing when status changes from Cancelled (4) to Packed (1) or Out for Delivery (2)
			} elseif ($status == 1) {
				$this->deduct_inventory($id);
			}
			$resp['status'] = 'success';
			$this->settings->set_flashdata("success", "Order status successfully updated.");
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function deduct_inventory($order_id){
		// Retrieve all products in the order list
		$order_list = $this->conn->query("SELECT * FROM `order_list` WHERE order_id = '{$order_id}' ");
		if ($order_list->num_rows > 0) {
			while ($order_data = $order_list->fetch_assoc()) {
				$product_id = $order_data['product_id'];
				$quantity = $order_data['quantity'];
				$size = $order_data['size'];
	
				// Deduct the quantity from the 'inventory' table based on the product_id, quantity, and size
				$inventory_update = $this->conn->query("UPDATE `inventory` SET `quantity` = `quantity` - '{$quantity}' WHERE `product_id` = '{$product_id}' AND `size` = '{$size}' ");
				if (!$inventory_update) {
					// Error updating inventory, handle accordingly
					$this->conn->error;
				}
			}
		}
	}	
	function add_inventory($order_id){
		// Retrieve all products in the order list
		$order_list = $this->conn->query("SELECT * FROM `order_list` WHERE order_id = '{$order_id}' ");
		if ($order_list->num_rows > 0) {
			while ($order_data = $order_list->fetch_assoc()) {
				$product_id = $order_data['product_id'];
				$quantity = $order_data['quantity'];
				$size = $order_data['size'];
	
				// Add the quantity back to the 'inventory' table based on the product_id, quantity, and size
				$inventory_update = $this->conn->query("UPDATE `inventory` SET `quantity` = `quantity` + '{$quantity}' WHERE `product_id` = '{$product_id}' AND `size` = '{$size}' ");
				if (!$inventory_update) {
					// Error updating inventory, handle accordingly
					$this->conn->error;
				}
			}
		}
	}
	function pay_order(){
		extract($_POST);
		$update = $this->conn->query("UPDATE `orders` set `paid` = '1' where id = '{$id}' ");
		if($update){
			$resp['status'] ='success';
			$this->settings->set_flashdata("success"," Order payment status successfully updated.");
		}else{
			$resp['status'] ='failed';
			$resp['err'] =$this->conn->error;
		}
		return json_encode($resp);
	}
	function update_account(){
		extract($_POST);
		$data = "";
		if(!empty($password)){
			$_POST['password'] = md5($password);
			if(md5($cpassword) != $this->settings->userdata('password')){
				$resp['status'] = 'failed';
				$resp['msg'] = "Current Password is Incorrect";
				return json_encode($resp);
				exit;
			}

		}
		$check = $this->conn->query("SELECT * FROM `clients`  where `email`='{$email}' and `id` != $id ")->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Email already taken.";
			return json_encode($resp);
			exit;
		}
		foreach($_POST as $k =>$v){
			if($k == 'cpassword' || ($k == 'password' && empty($v)))
				continue;
				if(!empty($data)) $data .=",";
					$data .= " `{$k}`='{$v}' ";
		}
		$save = $this->conn->query("UPDATE `clients` set $data where id = $id ");
		if($save){
			foreach($_POST as $k =>$v){
				if($k != 'cpassword')
				$this->settings->set_userdata($k,$v);
			}
			
			$this->settings->set_userdata('id',$this->conn->insert_id);
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function finish_order() {
		extract($_POST);
	
		// Validate input data
		if (!isset($id) || empty($id)) {
			$resp['status'] = 'failed';
			$resp['err'] = 'Invalid order ID';
			return json_encode($resp);
		}
	
		// Retrieve the order details from the orders table
		$qry = $this->conn->query("SELECT * FROM `orders` WHERE id = '{$id}'");
		$order = $qry->fetch_assoc();
	
		// Check if the order is delivered (status = 3) or cancelled (status = 4)
		if ($order['status'] == 3 && $order['paid'] == 1 || $order['status'] == 4) {
			// Insert the order details into the completed_orders table
			$date_completed = date("Y-m-d H:i:s"); // Current date and time
			$insert_completed_orders = $this->conn->query("
				INSERT INTO `completed_orders` (`id`, `client_id`, `delivery_address`, `payment_method`, `amount`, `date_completed`, `status`)
				VALUES (
					'{$order['id']}',
					'{$order['client_id']}',
					'{$order['delivery_address']}',
					'{$order['payment_method']}',
					'{$order['amount']}',
					'{$date_completed}',
					'{$order['status']}'
				)
			");
	
			if (!$insert_completed_orders) {
				// Return error status if the insertion fails for completed_orders
				$resp['status'] = 'failed';
				$resp['err'] = $this->conn->error;
				return json_encode($resp);
			}
	
			// Insert the order details into the completed_order_list table using data from order_list table
			$order_list_qry = $this->conn->query("SELECT * FROM `order_list` WHERE order_id = '{$id}'");
			$data = '';
			while ($row = $order_list_qry->fetch_assoc()) {
				if (!empty($data)) $data .= ", ";
				$data .= "(
					'{$row['order_id']}',
					'{$row['product_id']}',
					'{$row['size']}',
					'{$row['quantity']}',
					'{$row['price']}',
					'{$row['total']}'
				)";
			}
			if (!empty($data)) {
				$insert_completed_order_list = $this->conn->query("
					INSERT INTO `completed_order_list` (`order_id`, `product_id`, `size`, `quantity`, `price`, `total`)
					VALUES {$data}
				");
				if (!$insert_completed_order_list) {
					// Return error status if the insertion fails for completed_order_list
					$resp['status'] = 'failed';
					$resp['err'] = $this->conn->error;
					return json_encode($resp);
				}
			}
	
			// Delete the order and related details from the orders and order_list tables
			$delete_orders = $this->conn->query("DELETE FROM `orders` WHERE id = '{$id}'");
			$delete_order_list = $this->conn->query("DELETE FROM `order_list` WHERE order_id = '{$id}'");
	
			if ($delete_orders && $delete_order_list) {
				// Return success status
				$resp['status'] = 'success';
				$this->settings->set_flashdata('success', "Order successfully finished and moved to completed orders");
			} else {
				// Return error status if any of the delete queries fail
				$resp['status'] = 'failed';
				$resp['err'] = $this->conn->error;
			}
		} else {
			// Return error status if the order is not paid and delivered or cancelled
			$resp['status'] = 'failed';
			$resp['err'] = 'Order must be paid and delivered to finish it';
		}
	
		return json_encode($resp);
	}	
	
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_product':
		echo $Master->save_product();
	break;
	case 'delete_product':
		echo $Master->delete_product();
	break;
	case 'save_inventory':
		echo $Master->save_inventory();
	break;
	case 'delete_inventory':
		echo $Master->delete_inventory();
	break;
	case 'register':
		echo $Master->register();
	break;
	case 'add_to_cart':
		echo $Master->add_to_cart();
	break;
	case 'update_cart_qty':
		echo $Master->update_cart_qty();
	break;
	case 'delete_cart':
		echo $Master->delete_cart();
	break;
	case 'empty_cart':
		echo $Master->empty_cart();
	break;
	case 'delete_img':
		echo $Master->delete_img();
	break;
	case 'place_order':
		echo $Master->place_order();
	break;
	case 'update_order_status':
		echo $Master->update_order_status();
	break;
	case 'pay_order':
		echo $Master->pay_order();
	break;
	case 'update_account':
		echo $Master->update_account();
	break;
	case 'delete_order':
		echo $Master->delete_order();
	break;
	case 'finish_order':
		echo $Master->finish_order();
	break;
	default:
		// echo $sysset->index();
		break;
}