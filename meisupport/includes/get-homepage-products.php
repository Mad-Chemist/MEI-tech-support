<?php
	$getProducts = mysql_query('SELECT * FROM `44aae_meiadmin_products` WHERE `enabled`=1'); //grabs all products
	echo "<div id='all-products'>";
	while ($prow=mysql_fetch_array($getProducts)) {
		$build = '';
		$build.= '<div class="product">';
		$build.= '<div class="title">'.$prow['title'].'</div>';
		$build.= ($prow['images'] === '') ? '<img src="/images/blank.jpg">':'<img src="'.$prow['images'].'">';
		$build.= '<a href="index.php?option=com_mei&view=product&layout=item&id='.$prow['meiadmin_product_id'].'&Itemid=105" class="bttn">View</a>';
		$build.= '<div class="info">';
		$build.=  $prow['description'];
		$build.= '</div></div>';   
		echo $build; 
	}
	echo "</div>";
	echo "<div id='my-products'>";
	if ($user->id > 0) { //checks if the user has an ID
		$getMyProd = mysql_query('SELECT `products` FROM `44aae_meiadmin_customers` WHERE `fk_user_id`="'.$user->id.'"'); //grabs product id assigned to user
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
			$getProducts = mysql_query('SELECT * FROM `44aae_meiadmin_products` WHERE `enabled`=1 '.$getMyProd); //uses sql query string to find products
			while ($prow=mysql_fetch_array($getProducts)) {
				$build = '';
				$build.= '<div class="product">';
				$build.= '<div class="title">'.$prow['title'].'</div>';
				$build.= '<img src="'.$prow['images'].'">';
				$build.= '<a href="index.php?option=com_mei&view=product&layout=item&id='.$prow['meiadmin_product_id'].'&Itemid=105" class="bttn">View</a>';
				$build.= '<div class="info">';
				$build.=  $prow['description'];
				$build.= '</div></div>';   
				echo $build; 
			}
		}
	} 
	echo "</div>";
?>

