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
			
		$cn_id = $_GET['cn_id'];
        $cn_price = $_GET['cn_price'];
		$cn_cakename = $_GET['cn_cakename'];
		$cn_desc = $_GET['cn_desc'];
		$cn_images = $_GET['cn_images'];
		$size_id = $_GET['size_id'];
       
		$sql = "UPDATE `caken` SET `cn_id` = '$cn_id', `cn_cakename` = '$cn_cakename', `cn_desc` = '$cn_desc',  `cn_price` = '$cn_price', `cn_images` = '$cn_images', `size_id` = '$size_id' WHERE cn_id = '$cn_id'";

		$result = mysqli_query($link, $sql);

		if ($result) {
			echo "true";
		} else {
			echo "false";
		}

	} else echo "Welcome Edit API Cake";
   
}

	mysqli_close($link);
?>