<script>
function fillFromAPI(user) {	
	var url = (user > 1) ?  "api.php?user=" + user: "api.php";	
	jQuery.ajax({	  
		dataType: "json",	  
		url: url,	  
		success: function(data){		
			jQuery.each(data, function(i, obj) {			
				jQuery('#meiproduct').append("<option value='"+obj.title+"'>"+obj.title+"</option>");		
			});	  
		}	
	});
}
</script>

<style>
#main-article {overflow:hidden;
.formControls {
    margin: 0px !important;
}
h3.typeTitle {
    width: 90% !important;
    padding: 5px 2.5% !important;
}
.formResponsive .formHorizontal .rsform-block {
    margin-bottom: 2px !important;
}
</style>
<?php
echo "<script>fillFromAPI('".$user->id."'); </script>";
$techEm = mysql_query("SELECT `44aae_meiadmin_customers`.`support_email`, `44aae_meiadmin_customers`.`telephone`, `44aae_meiadmin_customers`.`company`, `44aae_users`.`name`, `44aae_users`.`email` FROM `44aae_meiadmin_customers`
INNER JOIN `44aae_users` on `44aae_meiadmin_customers`.`fk_user_id` = `44aae_users`.`id` WHERE `fk_user_id` = ".$user->id);

$techEm 		= 	mysql_fetch_assoc($techEm); 

$usersName 		=	$techEm['name'];
$companyName 	= 	$techEm['company'];
$usersEmail		=	$techEm['email'];
$usersPhone		=	$techEm['telephone'];
$techEm 		= 	$techEm['support_email'];



$formLayout = str_replace('<input type="hidden" name="form[techsupemail]" id="techsupemail" value=""  />', '<input type="hidden" name="form[techsupemail]" id="techsupemail" value="'.$techEm.'" upd="true" />', $formLayout);
$formLayout = str_replace('<input type="text" value="" size="20"  name="form[Company]" id="Company"  class="rsform-input-box"/>', '<input type="text" value="'.$companyName.'" size="20" name="form[Company]" id="Company" class="rsform-input-box" upd="true">', $formLayout);
$formLayout = str_replace('<input type="text" value="" size="20"  name="form[Phone]" id="Phone"  class="rsform-input-box"/>', '<input type="text" value="'.$usersPhone.'" size="20" name="form[Phone]" id="Phone" class="rsform-input-box" upd="true">', $formLayout);
$formLayout = str_replace('<input type="text" value="" size="20"  name="form[Full Name]" id="Full Name"  class="rsform-input-box"/>', '<input type="text" value="'.$usersName.'" size="20" name="form[Full Name]" id="Full Name" class="rsform-input-box" upd="true">', $formLayout);
$formLayout = str_replace('<input type="text" value="" size="20"  name="form[Email]" id="Email"  class="rsform-input-box"/>', '<input type="text" value="'.$usersEmail.'" size="20" name="form[Email]" id="Email" class="rsform-input-box" upd="true">', $formLayout);

?>



<?php echo "<xmp>".$formLayout ."</xmp>";  ?>