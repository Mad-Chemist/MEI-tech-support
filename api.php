<?php
	header('Content-type: application/json');
	include('configuration.php');
	$jc = new JConfig();
	mysql_connect($jc->host, $jc->user, $jc->password) or die("Cannot connect to the database");
	mysql_select_db($jc->db);

	$userID = $_GET['user'];
	if (is_numeric($userID)) {
		$getMyProd = mysql_query('SELECT `products` FROM `44aae_meiadmin_customers` WHERE `fk_user_id`="'.$userID.'"'); //grabs product id assigned to user
		$getMyProd = mysql_fetch_assoc($getMyProd);
		$getMyProd = $getMyProd['products'];
		if (strlen($getMyProd) > 0) { //if array isn't empty, continue
			$getMyProd = explode(",", $getMyProd); //turns string into array and removes commas
			foreach ($getMyProd as $key => $prodID) { 
				$addOR = ($key < count($getMyProd) - 1) ? " OR ": ""; //correctly formats sql query depending on position in array
				$addAND = ($key == 0) ? $addAND = "  AND  " : "";
				$getMyProd[$key] = $addAND."`meiadmin_product_id` ='".$prodID."'".$addOR; //sets array
			}
			$getMyProd = implode("", $getMyProd); //turns array back into a string
			$getProducts = mysql_query('SELECT `title` FROM `44aae_meiadmin_products` WHERE `enabled`=1 '.$getMyProd); //uses sql query string to find products
		} 
	}
	else $getProducts = mysql_query('SELECT `title` FROM `44aae_meiadmin_products` WHERE `enabled`=1');
	$rows = array();
	while($r = mysql_fetch_assoc($getProducts)) {
	  $rows[] = $r;
	}
	echo json_encode($rows);
?>