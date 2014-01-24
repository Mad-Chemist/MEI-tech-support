jQuery(document).ready(function() {
	setUpBgFX(); /*navigation fx*/
	moveFilterToLeft(); /*move filter to left hand side*/
	changeMeiAdminHeadings(); /* change title of product pages (edit else products main page*/
	hideHomepageLogin();/*product navigation fx*/
	addHomepageProductNavFX();/*login, hide unwanted elements*/
	setUpAccordion(); /*accordian sliders*/		
	setUpMultiSelect(); /*sets multi select for region and channel in manage file section*/
});

function moveBgFX(bg,anchor,color) {
	jQuery(bg).stop().animate({width:jQuery(anchor).width() + 20, left : jQuery(anchor).position().left + 10});
	if (color) jQuery(anchor).css('color',color)
}
function setUpBgFX() {
	moveBgFX('#nav-active-bg','.nav.menu li.active.current');
	jQuery("ul.nav.menu li").on("hover", function() {
		 moveBgFX('#nav-active-bg', this);
	});
	jQuery("#nav").on("mouseleave", function() {
		 moveBgFX('#nav-active-bg','.nav.menu li.active.current');
	});
}
function moveFilterToLeft() {
	if (jQuery('#j-sidebar-container select').length > 0) {
		jQuery('#j-main-container').css({'width':'79%','margin-left':'20px'})
	}
}
function changeMeiAdminHeadings() { 
	if (jQuery('body').hasClass('com_meiadmin') !== true) return;

	if (jQuery('body').hasClass('view-product') === true && jQuery('body').hasClass('task-edit') === true) {
		jQuery('#main-article h1').html('<span style="font-weight:normal;">'+vLanguage[cvlang][3]['editP']+'</span> '+jQuery('#title').val());
	}
	else if (jQuery('body').hasClass('view-products') === true) {
		jQuery('#main-article h1').empty().css('visibility', 'hidden');
		setTimeout(function() { 
			if (jQuery('#cat_id_chzn a span').text() != "- Family -") jQuery('#main-article h1').html("<span style='font-weight:normal;'>"+vLanguage[cvlang][3]['viewPF']+"</span> "+jQuery('#cat_id_chzn a span').text()); 
			else jQuery('#main-article h1').text(vLanguage[cvlang][3]['viewAP']); 
			jQuery('#main-article h1').css('visibility','visible').hide().fadeIn('slow');
		},200);
	}
	else if (jQuery('body').hasClass('view-customer') === true && jQuery('body').hasClass('task-edit') === true) {
		jQuery('#main-article').before("<h1 style='font-weight:normal;'>"+vLanguage[cvlang][3]['editC']+" <b>"+jQuery('input#name').val()+"</b></h1>");
	}
	else if (jQuery('body').hasClass('view-customers') === true && jQuery('body').hasClass('no-task') !== null) {
		jQuery('#main-article').before("<h1>"+vLanguage[cvlang][3]['viewC']+"</h1>");
	}
}
function hideHomepageLogin() {
	if (jQuery('#body-login form').length > 0) { 
	  	jQuery('.posttext').appendTo('.userdata');
	  	jQuery('#form-login-submit').appendTo('.userdata');
		jQuery('.unstyled').remove();
	}
}
function addHomepageProductNavFX() {
	if (jQuery('#prod-active-bg').length > 0) { 
		if (jQuery('#my-products').text().length == 0) jQuery('#bottom-pnav a:nth-child(3)').addClass('disabled');
		moveBgFX('#prod-active-bg','.active-nav', '#fff');
		jQuery("#bottom-pnav a").not('.disabled').on("hover", function() {
			 jQuery('#bottom-pnav a').css('color','inherit')
			 moveBgFX('#prod-active-bg', this, '#fff');
		});
		jQuery("#bottom-pnav").on("mouseleave", function() {
			 jQuery('#bottom-pnav a').css('color','inherit')
			 moveBgFX('#prod-active-bg','.active-nav', '#fff');
		});
		/*product nav div change*/
		jQuery('#bottom-pnav a:not(.disabled)').click(function() {
			if (!jQuery(this).hasClass('active-nav')) {
				jQuery('#bottom-pnav a').removeClass('active-nav');
				jQuery(this).addClass('active-nav');
				jQuery('#all-products, #my-products').slideToggle();
				}
		})
		if	(jQuery('#bottom-pnav a:not(.disabled)').length === 2) jQuery('#bottom-pnav a').mouseover().click();
	}
}
function setUpAccordion() {
	if (jQuery('.file_table').length > 0) { 
		//adds the search box to product page
		jQuery('div[name=firmware] table').prepend("<tr class='firmware-search'><td colspan='3'><span style='float:right;'>search: <input type='text' class='firm-search'></span></td></tr>");
		//searches when typed into:
		jQuery('.firm-search').keyup(function(){
			jQuery(this).parents('table').find('tr:not(.firmware-search, .file-table-head)').hide();
			jQuery(this).parents('table').find('tr[class*='+jQuery(this).val().split(" ").join("-")+']:not(.firmware-search, .file-table-head)').show();

			if (jQuery(this).val() == "") 	jQuery(this).parents('table').find('tr:not(.firmware-search, .file-table-head)').show();
		});
		/*adds arrow to product section head*/
		jQuery('.section-head').append('<img src="/images/arrow-open.png" class="arrowOpen"><img src="/images/arrow-down.png" style="display:none;" class="arrowClose">');
		jQuery('.file_table, .section-children').slideUp(0); 
		jQuery('h4.typeTitle').click(function() { 
			if (jQuery(this).next('.file_table').css('display') == 'none') jQuery('.file_table').not(this).slideUp(); 
			jQuery(this).next('.file_table').slideToggle();
		});
		/* adds functionality to section heads*/
		jQuery('h2.section-head').click(function() { 
			jQuery('.arrowOpen').show(); jQuery('.arrowClose').hide();
			if (jQuery(this).next('.section-children').css('display') == 'none') { 
				jQuery('.section-children').not(this).slideUp(); 
				jQuery(this).children('.arrowClose').show();
				jQuery(this).children('.arrowOpen').hide();
			}
			jQuery(this).next('.section-children').slideToggle();
		});	
	}
}
function setUpMultiSelect() {
	var check = jQuery('body.com_meiadmin.view-file.task-edit form#adminForm');
	if (check.length === 1) {
			var vbChan = ['Gaming','Retail','Transportation','Vending'], vbId = [3,4,7,8], vbVal = jQuery('#channel').val(), vbOpts; //channel vars
			var vbReg = ['Americas','EMEA','APR'], vbRid = [1,2,3], vbRval = jQuery('#region').val(), vbRopts; //region vars
			remakeSubmit();
			setMultiselectUp();
	}

	function setMultiselectUp() {
		jQuery('#channel').attr({'id':'oldc','name':'oldc'});
		jQuery.each(vbId, function(index,value) { 
			vbOpts+= (vbVal.indexOf(value) != -1) ? '<option value="'+value+'" selected="selected">'+vbChan[index]+'</option>' :'<option value="'+value+'">'+vbChan[index]+'</option>'; 
		});
		jQuery('#oldc').after('<select id="channel" name="channel" multiple required>'+vbOpts+'</select>');
		jQuery('#region').attr({'id':'oldr','name':'oldr'});
		jQuery.each(vbRid, function(index,value) { 
			vbRopts+= (vbRval.indexOf(value) != -1) ? '<option value="'+value+'" selected="selected">'+vbReg[index]+'</option>' :'<option value="'+value+'">'+vbReg[index]+'</option>'; 
		});

		jQuery('#oldr').after('<select id="region" name="region" multiple required>'+vbRopts+'</select>');

		jQuery('#oldc, #oldr').remove();
	}
	function trickSelect() {
		var chanVal = jQuery('select#channel').val(), regVal = jQuery('select#region').val();
		chanVal = chanVal.toString().split(',').join('',''), regVal = regVal.toString().split(',').join('','');
		jQuery('select#channel').attr('id','channel2');
		jQuery('select#region').attr('id','region2');

		check.append('<input type="hidden" id="channel" value="'+chanVal+'"><input type="hidden" id="region" value="'+regVal+'">');
	}
	function remakeSubmit() {
		Joomla.submitform = function (a,b){
			var chanVal = jQuery('select#channel').val(), regVal = jQuery('select#region').val();
			chanVal = chanVal.toString().split(',').join('',''), regVal = regVal.toString().split(',').join('','');
			jQuery('select#channel').attr({'id':'channel2','name':'channel2[]'});
			jQuery('select#region').attr({'id':'region2','name':'region2[]'});

			check.append('<input type="hidden" id="channel" value="'+chanVal+'" name="channel"><input type="hidden" id="region" value="'+regVal+'" name="region">');

			"undefined"===typeof b&&(b=document.getElementById("adminForm")); 
			"undefined"!==typeof a&&(b.task.value=a); 
			if("function"==typeof b.onsubmit)b.onsubmit();
			"function"==typeof b.fireEvent&&b.fireEvent("submit");b.submit();
		} 
	}
}