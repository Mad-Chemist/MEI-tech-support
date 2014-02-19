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
		setTimeout(function() { 
			if (jQuery('#cat_id_chzn a span').text() != "- Family -") jQuery('#main-article h1').html("<span style='font-weight:normal;'>"+vLanguage[cvlang][3]['viewPF']+"</span> "+jQuery('#cat_id_chzn a span').text()); 
			else jQuery('#main-article h1').text(vLanguage[cvlang][3]['viewAP']); 
		},100);
	}
	else if (jQuery('body').hasClass('view-customer') === true && jQuery('body').hasClass('task-edit') === true) {
		jQuery('#main-article').before("<h1 style='font-weight:normal;'>"+vLanguage[cvlang][3]['editC']+" <b>"+jQuery('input#name').val()+"</b></h1>");
	}
	else if (jQuery('body').hasClass('view-customers') === true && jQuery('body').hasClass('no-task') !== null) {
		jQuery('#main-article').before("<h1>"+vLanguage[cvlang][3]['viewC']+"</h1>");
	}
	else if (jQuery('body').hasClass('view-families') === true) {
		jQuery('#main-article h1').text(vLanguage[cvlang][3]['viewPFs']); 
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
	if (jQuery('.file_table').length > 0 ) { 
		//adds the search box to product page
		if (jQuery('div[name=firmware] table').length > 0 && jQuery('body').hasClass('view-customer') != true) {
			jQuery('div[name=firmware] table').prepend("<tr class='firmware-search'><td colspan='3'><span style='float:right;'>search: <input type='text' class='firm-search'></span></td></tr>");
			//searches when typed into:
			jQuery('.firm-search').keyup(function(){
				jQuery(this).parents('table').find('tr:not(.firmware-search, .file-table-head)').hide();
				jQuery(this).parents('table').find('tr[class*='+jQuery(this).val().split(" ").join("-").toLowerCase()+']:not(.firmware-search, .file-table-head)').show();

				if (jQuery(this).val() == "") 	jQuery(this).parents('table').find('tr:not(.firmware-search, .file-table-head)').show();
			});
		}
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
	if (jQuery('body').hasClass('view-customer') != true) jQuery('h2.section-head:nth-child(1)').click();
}
function setUpMultiSelect() {
	var check = jQuery('body.com_meiadmin.view-file.task-edit form#adminForm');
	if (check.length === 1) {
			var vbChan = [vLanguage[cvlang][4]['gaming'], vLanguage[cvlang][4]['retail'], vLanguage[cvlang][4]['transportation'],vLanguage[cvlang][4]['vending'],vLanguage[cvlang][4]['financial']], vbId = [3,4,7,8,9], vbVal = jQuery('#channel').val(), vbOpts = ""; //channel vars
			var vbReg = ['Americas','EMEA','APR'], vbRid = [1,2,3], vbRval = jQuery('#region').val(), vbRopts; //region vars
			remakeSubmit();
			setMultiselectUp();
			//also initiate multi select drags
			initiateCustomerSwapping();
	}

	function setMultiselectUp() {
		jQuery('#channel').attr({'id':'oldc','name':'oldc'});
		jQuery.each(vbId, function(index,value) { 
			vbOpts+= (vbVal.indexOf(value) != -1) ? '<option value="'+value+'" selected="selected">'+vbChan[index]+'</option>' :'<option value="'+value+'">'+vbChan[index]+'</option>'; 
		});
		jQuery('#oldc').after('<select id="channel2" name="channel2" multiple required>'+vbOpts+'</select>');
		jQuery('#region').attr({'id':'oldr','name':'oldr'});

		vbRopts = "";
		jQuery.each(vbRid, function(index,value) { 
			vbRopts+= (vbRval.indexOf(value) != -1) ? '<option value="'+value+'" selected="selected">'+vbReg[index]+'</option>' :'<option value="'+value+'">'+vbReg[index]+'</option>'; 
		});

		jQuery('#oldr').after('<select id="region2" name="region2" multiple required>'+vbRopts+'</select>');

		jQuery('#oldc, #oldr').remove();

		check.append('<input type="text" id="channel"  name="channel" style="display:none;"><input type="text" id="region"  name="region" style="display:none;">');
	}
	function updateSelectValues() {
		var chanVal = jQuery('select#channel2').val(), regVal = jQuery('select#region2').val();
		if (null== regVal) regVal = "";
		if (null== chanVal) chanVal = "";
		chanVal = chanVal.toString().split(',').join('',''), regVal = regVal.toString().split(',').join('','');
		jQuery('#channel').val(chanVal);
		jQuery('#region').val(regVal);
	}
	function remakeSubmit() {
		Joomla.submitform = function (a,b){
			updateNewCustomerValues();
			updateSelectValues();
			"undefined"===typeof b&&(b=document.getElementById("adminForm")); 
			"undefined"!==typeof a&&(b.task.value=a); 
			if("function"==typeof b.onsubmit)b.onsubmit();
			"function"==typeof b.fireEvent&&b.fireEvent("submit");
			if (a != "cancel") {
				var confirmCheck = confirm(vLanguage[cvlang][2]['confirm']);
				if (confirmCheck == false) return;
			}
			b.submit();
		} 
	}
}



function initiateCustomerSwapping() {
	//add drag and drop containers to body
	jQuery('head').append('<script src="/templates/meisupport/js/jquery-ui-1.10.4.custom.min.js"></script><script src="/templates/meisupport/js/jquery-ui.touch-punch.min.js"></script>');
	jQuery('#fileAccessFields').append('<h3>Customer Access:</h3> <div id="sort-access"> <label> <span id="help" title="'+vLanguage[cvlang][5]['help']+'">?</span> '+vLanguage[cvlang][5]['search']+' <input type="text" id="customer-search-box"></label> <span>'+vLanguage[cvlang][5]['allowed']+'</span> <ul id="allowed-customer-access" class="connectedSortable"></ul> <span>'+vLanguage[cvlang][5]['unassigned']+'</span> <ul id="all-customer-access" class="connectedSortable"></ul> <span>'+vLanguage[cvlang][5]['denied']+'</span> <ul id="no-customer-access" class="connectedSortable"></ul> <div style="clear:both;"></div> </div> ');	
	relocateAllowedCustomers();
	relocateDeniedCustomers();
	relocateUnassignedCustomers();
	removeOldContainers();
	setNewHiddenContainers();
	updateNewCustomerValues();

	jQuery( "#allowed-customer-access, #all-customer-access, #no-customer-access" ).sortable({
      connectWith: ".connectedSortable"
    }).disableSelection();

    searchCustomers();
}

function relocateAllowedCustomers() {
	//moves selected customers to selected customer's container
	jQuery("#access_account option:selected" ).each(function() {
	     var tVal = jQuery(this).val(), tText = jQuery(this).text();
	     jQuery(this).remove();
	     jQuery('#allowed-customer-access').append('<li value="'+tVal+'" inner="'+tText.toLowerCase()+'">'+tText+'</li>');

	});
}
function relocateDeniedCustomers() {
	//moves denied access to deny container
	/*checks value.  if value is null, doesnt add anything to no-access list.  Fixes the bug of always having one li added*/
	if (jQuery('#deny_access').val() == "") return;
	var dAcc = jQuery('#deny_access').val().split(','), dAccI = 0;
	while (dAccI < dAcc.length) {
		var this1 = jQuery("#access_account option[value="+dAcc[dAccI]+"]" );
		jQuery('#no-customer-access').append('<li value="'+dAcc[dAccI]+'" inner="'+this1.text().toLowerCase()+'">'+this1.text()+'</li>');
		this1.remove();
		dAccI++;
	}
}
function relocateUnassignedCustomers() {
	//moves unselected values to all customers container
	jQuery("#access_account option" ).each(function() {
	     var tVal = jQuery(this).val(), tText = jQuery(this).text();
	     jQuery(this).remove();
	     jQuery('#all-customer-access').append('<li value="'+tVal+'" inner="'+tText.toLowerCase()+'">'+tText+'</li>');

	});
}

function removeOldContainers() {
	//removes the old containers in preparation to set new hidden containers up
	jQuery("#access_account").parents('.control-group').remove();
	jQuery("#deny_access").parents('.control-group').remove();
}

function setNewHiddenContainers() {
	//appends hidden fields with proper id's
	jQuery('#fileAccessFields').append("<input type='text' style='display:none;' name='access_account' id='access_account'><input type='text' style='display:none;'  name='deny_access' id='deny_access'>");
}

function updateNewCustomerValues() {
	//updates the hidden fields
	var updArr = [];
	jQuery('#allowed-customer-access li').each(function() { 
		updArr.push(jQuery(this).val());
	})
	jQuery('#access_account').val(updArr.join());
	var updArr = [];
	jQuery('#no-customer-access li').each(function() { 
		updArr.push(jQuery(this).val());
	})
	jQuery('#deny_access').val(updArr.join());	
}


function searchCustomers() {
	jQuery('#customer-search-box').keyup(function(){
		jQuery('ul.connectedSortable li').hide();
		jQuery('ul.connectedSortable li[inner*="'+jQuery('#customer-search-box').val().toLowerCase()+'"]').show();
		if (jQuery(this).val() == "") 	jQuery('ul.connectedSortable li').show();
	});
}