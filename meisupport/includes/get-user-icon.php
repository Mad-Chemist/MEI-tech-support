<?php
	$getuserImage 		=	mysql_query('SELECT  `image` FROM  `44aae_meiadmin_customers` WHERE  `fk_user_id` = '.$user->id); //grabs all products
	$getuserImage 		= 	mysql_fetch_assoc($getuserImage);  
	$getuserImage 		= 	$getuserImage['image'];
	if (strlen($getuserImage) > 0) echo '<div class="userImg"><a href="/index.php?option=com_mei&view=customer&layout=form&Itemid=212" title="Change your Icon"><img src="'.$getuserImage.'"></a></div>';
	else echo '<div class="userImg"><a href="/index.php?option=com_mei&view=customer&layout=form&Itemid=212" title="Change your Icon"><img src="/images/user-images/mei-default.jpg"></a></div>';
?>

