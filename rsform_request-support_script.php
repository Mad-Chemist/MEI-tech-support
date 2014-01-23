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
$techEm = mysql_query("SELECT `support_email` FROM `44aae_meiadmin_customers` WHERE `fk_user_id` = ".$user->id);
$techEm 		= 	mysql_fetch_assoc($techEm);  
$techEm 		= 	$techEm['support_email'];
$formLayout = str_replace('<input type="hidden" name="form[techsupemail]" id="techsupemail" value=""  />', '<input type="hidden" name="form[techsupemail]" id="techsupemail" value="'.$techEm.'" upd="true" />', $formLayout);
?>