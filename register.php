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
				
		$Name = $_GET['Name'];
		$User = $_GET['User'];
		$Password = $_GET['Password'];
		$ChooseType = $_GET['chooseType'];
		$Phone = $_GET['Phone'];
		$Address = $_GET['Address'];
		$Lat = $_GET['Lat'];
		$Lng = $_GET['Lng'];
        $Token = $_GET['Token'];
							
		$sql = "INSERT INTO `c_usertable`(`id`,`chooseType`,`Name`,`User`,`Password`,`Phone`,`Address`,`Lat`,`Lng`,`Token`) VALUES (Null,'$ChooseType','$Name','$User','$Password','$Phone','$Address','$Lat','$Lng','$Token')";

		$result = mysqli_query($link, $sql);

		if ($result) {
			echo "true";
		} else {
			echo "false";
		}

	} else echo "Welcome cakeorder data User";
   
}
	mysqli_close($link);
?>