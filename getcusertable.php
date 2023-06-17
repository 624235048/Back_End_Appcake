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

	if (isset($_GET['isAdd']) && $_GET['isAdd'] == 'true') {
		$query = "SELECT * FROM c_usertable";
		$result = mysqli_query($link, $query);

		if ($result) {
			$rows = array();
			while($row = mysqli_fetch_assoc($result)) {
				$rows[] = $row;
			}
			echo json_encode($rows);
		} else {
			echo "Failed to get Cusertable data.";
		}
	} else {
		echo "Invalid request.";
	}

	mysqli_close($link);
?>
