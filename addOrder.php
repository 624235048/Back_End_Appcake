<?php
	include 'connected.php';
	header("Access-Control-Allow-Origin: *");

if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    
    exit;
}

if (!$link->set_charset("utf8")) {
    printf("Error loading character set utf8: %s\n", $link->error);
    exit();
	}

if (isset($_GET)) {
	if ($_GET['isAdd'] == 'true') {
		
        $order_date_time = $_GET['order_date_time'];
        $cake_id = $_GET['cake_id'];
        $user_id = $_GET['user_id'];
        $user_name = $_GET['user_name'];
        $imgcake = $_GET['imgcake'];
        $text = $_GET['text'];
        $cake_flavor =$_GET['cake_flavor'];
        $size = $_GET['size'];
        $price = $_GET['price'];
        $amount = $_GET['amount'];
        $sum = $_GET['sum'];
        $pickup_date = $_GET['pickup_date'];
        $payment_status = $_GET['payment_status'];
        $status = $_GET['status'];
        $distance = $_GET['distance'];
        $transport  = $_GET['transport'];

	
							
		$sql = "INSERT INTO `order_table`(`order_id`, `order_date_time`, `cake_id`, `user_id`, `user_name`, `size`, `text`, `cake_flavor`, `imgcake`, `price`, `amount`, `sum`, `pickup_date`, `status`, `payment_status`, `distance`, `transport`) VALUES (Null,'$order_date_time','$cake_id','$user_id','$user_name','$size','$text','$cake_flavor','$imgcake','$price','$amount','$sum','$pickup_date','$payment_status','$status','$distance','$transport')";

		$result = mysqli_query($link, $sql);

		if ($result) {
			echo "true";
		} else {
			echo "false";
		}

	} else echo "Welcome AddOrder data Cake";
   
}
	mysqli_close($link);
?>