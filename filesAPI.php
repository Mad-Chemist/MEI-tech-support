<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Files</title>
	<style>
	* { margin:0px; padding:0px; border:none; position:relative; z-index:1; }
	html, body { width:100%; height:100%; font-family:helvetica; }
	table { width:100%; }
	.product-heading span {
		margin-right: 10px;
		font-weight:bold;
	}
	tr.product-heading {
    background: #B0B0B0;
}
tr.product-heading > td {
    padding: 10px 25px;
    border-top: 2px #333 solid;
    border-bottom: 2px #333 solid;
    text-align: left;
}
tr[data-filename] > td:first-child {
    padding: 0px 25px 10px 25px;
    word-break: break-word;
    line-height: 1.5;
    font-size: 15px;
    text-align: left;
}
tr > td {
    padding-bottom: 10px;
    text-align: center;
}
tr[data-filename]:nth-child(even) {
    background: #E6E6E6;
}
table {
    border-collapse: collapse;
    /* border-collapse: separate; */    border-color: #808080;
    border-spacing: 0px;
}

div#hover-bar {
    position: fixed;
    top: 0px;
    width: 100%;
    background: #fff;
}
div#hover-bar > #hover-headings > div {
    display: inline-block;
    text-align:center;
	font-weight:bold;
}
.floatingHeader {
  position: fixed;
  top: 0;
  visibility: hidden;
	background: #FFF;
	z-index: 10;
	border-top: 2px #333 solid;
	border-bottom: 2px #333 solid;
}
tr.floatingHeader > td {
    border: none;
}

	</style>
</head>
<body>
	<?php
	//header('Content-type: application/json');
	include('configuration.php');
	$jc = new JConfig();
	mysql_connect($jc->host, $jc->user, $jc->password) or die("Cannot connect to the database");
	mysql_select_db($jc->db);


	$getFiles = mysql_query(   "SELECT `44aae_meiadmin_products`.`title` AS 'Product Title', `44aae_meiadmin_files`.*, `44aae_meiadmin_file_versions`.*
								FROM `44aae_meiadmin_files` 
								INNER JOIN `44aae_meiadmin_products` 
								ON `44aae_meiadmin_files`.`fk_product_id`=`44aae_meiadmin_products`.`meiadmin_product_id`
								INNER JOIN `44aae_meiadmin_file_versions` 
								ON `44aae_meiadmin_files`.`meiadmin_file_id`=`44aae_meiadmin_file_versions`.`fk_file_id`  
								WHERE `44aae_meiadmin_files`.`enabled`=1 
								AND `44aae_meiadmin_files`.`current_version`=`44aae_meiadmin_file_versions`.`version` 
								ORDER BY `44aae_meiadmin_files`.`meiadmin_file_id`"
							);

	$productID 	=	0;
	$htmlEcho	=	"";
	$htmlEcho.="<table>"; 
	$htmlEcho.="<thead><tr id='table_headings'><th>File Name</th> <th>File Version</th> <th>File Type</th> <th>Download</th> <th>Edit</th></tr></thead>";
	while($r = mysql_fetch_assoc($getFiles)) {
		if ($productID != $r['fk_product_id']) {
			if ($productID == 0) $htmlEcho.="<tbody class='persist-area'>";
			else $htmlEcho.="</tbody><tbody class='persist-area'>";

			/*Set product id to next product*/
			$productID = $r['fk_product_id'];

			/*Add new product's heading*/
			$htmlEcho.="<tr class='product-heading persist-header' id='".$r['Product Title']."'><td colspan='5'><span>Product:</span>".$r['Product Title']."</td></tr>";
		}
		/*Version logic for custom versioning...*/
		$file_version = ($r['custom_version'] != NULL) ? $r['custom_version'] : $r['current_version'];

		/*Start adding into table*/
		$htmlEcho.="<tr title='".$r['title']."' data-filename='".strtolower(str_replace(" ", "-", $r['title']))."'>";
		$htmlEcho.="<td class='file_title'>".$r['title']."</td>";
		$htmlEcho.="<td class='file_version'>".$file_version."</td>";
		$htmlEcho.="<td class='file_type'>".$r['type'] ." > ".$r['section']."</td>";
		$htmlEcho.="<td class='file_download'><a href='https://cpi-grts.com/component/mei/?view=file&task=download&id=".$r['fk_file_id']."' target='_blank'>Download</a></td>";
		$htmlEcho.="<td class='file_edit'><a href='/index.php?option=com_meiadmin&view=file&task=edit&pid=".$r['fk_product_id']."&id=".$r['fk_file_id']."&Itemid=126' target='_blank'>Edit</a></td>";
		$htmlEcho.="</tr>";
	}
	$htmlEcho.="</tbody></table>"; 
	echo $htmlEcho;
	//echo json_encode($rows);

	// function addTableRow() {

	// }
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
<script>
	$(document).ready(function() {
		initializeTable();
	});

	function initializeTable() {
		$('body').append("<div id='hover-bar'><div id='hover-headings'></div></div>");
		addTableHoverHeadings();
		$(window).resize(sizeTableHoverHeadings);
	}
	function addTableHoverHeadings() {
		$('tr > th').each(function() {
			$('#hover-bar #hover-headings').append('<div>'+$(this).text()+'</div>');
		});
		sizeTableHoverHeadings();
	}
	function sizeTableHoverHeadings() {
		var cur = 0;
		$('tr > th').each(function() {
			$('#hover-bar #hover-headings > div:nth('+cur+')').width($(this).outerWidth());
			cur++;
		});
	}



	function UpdateTableHeaders() {
   $(".persist-area").each(function() {
   
       var el             = $(this),
           offset         = el.offset(),
           scrollTop      = $(window).scrollTop(),
           floatingHeader = $(".floatingHeader", this)
       
       if ((scrollTop > offset.top) && (scrollTop < offset.top + el.height())) {
           floatingHeader.css({
            "visibility": "visible"
           });
       } else {
           floatingHeader.css({
            "visibility": "hidden"
           });      
       };
   });
}

// DOM Ready      
$(function() {

   var clonedHeaderRow;

   $(".persist-area").each(function() {
       clonedHeaderRow = $(".persist-header", this);
       clonedHeaderRow
         .before(clonedHeaderRow.clone())
         .css("width", clonedHeaderRow.width())
         .addClass("floatingHeader");
         
   });
   
   $(window)
    .scroll(UpdateTableHeaders)
    .trigger("scroll");
   
$('.floatingHeader').css('top',$('#hover-bar').height());
   $(window).resize(function() {
		/*Also, space the product heading*/
		$('.floatingHeader').css('top',$('#hover-bar').height());
	});
});
</script>
</body>
</html>