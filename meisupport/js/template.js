/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       3.2
 */

(function($)
{
	$(document).ready(function()
	{
		$('*[rel=tooltip]').tooltip()

		// Turn radios into btn-group
		$('.radio.btn-group label').addClass('btn');
		$(".btn-group label:not(.active)").click(function()
		{
			var label = $(this);
			var input = $('#' + label.attr('for'));

			if (!input.prop('checked')) {
				label.closest('.btn-group').find("label").removeClass('active btn-success btn-danger btn-primary');
				if (input.val() == '') {
					label.addClass('active btn-primary');
				} else if (input.val() == 0) {
					label.addClass('active btn-danger');
				} else {
					label.addClass('active btn-success');
				}
				input.prop('checked', true);
			}
		});
		$(".btn-group input[checked=checked]").each(function()
		{
			if ($(this).val() == '') {
				$("label[for=" + $(this).attr('id') + "]").addClass('active btn-primary');
			} else if ($(this).val() == 0) {
				$("label[for=" + $(this).attr('id') + "]").addClass('active btn-danger');
			} else {
				$("label[for=" + $(this).attr('id') + "]").addClass('active btn-success');
			}
		});
		/*schubert modifications*/

		/*homepage - login, hide unwanted elements*/
		if (jQuery('#body-login form').length > 0) { 
      	jQuery('.posttext').appendTo('.userdata');
      	jQuery('#form-login-submit').appendTo('.userdata');
		jQuery('.unstyled').remove();
 		}
 		/*move filter to left hand side*/
 		if (jQuery('#j-sidebar-container select').length > 0) {
 			jQuery('#j-main-container').css({'width':'79%','margin-left':'20px'})
 		}


 		/*function for fx*/
		function moveBgFX(bg,anchor,color) {
			jQuery(bg).stop().animate({width:jQuery(anchor).width() + 20, left : jQuery(anchor).position().left + 10});
			if (color) jQuery(anchor).css('color',color)
		}
		/*navigation fx*/
		moveBgFX('#nav-active-bg','.nav.menu li.active.current');
		jQuery("ul.nav.menu li").on("hover", function() {
			 moveBgFX('#nav-active-bg', this);
		});
		jQuery("#nav").on("mouseleave", function() {
			 moveBgFX('#nav-active-bg','.nav.menu li.active.current');
		});

		/*product navigation fx*/
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
		}
		/*product nav div change*/
		jQuery('#bottom-pnav a:not(.disabled)').click(function() {
			if (!jQuery(this).hasClass('active-nav')) {
				jQuery('#bottom-pnav a').removeClass('active-nav');
				jQuery(this).addClass('active-nav');
				jQuery('#all-products, #my-products').slideToggle();
				}
		})



		/*accordian sliders*/
		if (jQuery('.file_table').length > 0) { 
		//adds the search box to product page
		jQuery('div[name=firmware] table').prepend("<tr class='firmware-search'><td colspan='3'><span style='float:right;'>search: <input type='text' class='firm-search'></span></td></tr>");
		//searches when typed into:
		jQuery('.firm-search').keyup(function(){
			jQuery(this).parents('table').find('tr:not(.firmware-search, .file-table-head)').hide();
			jQuery(this).parents('table').find('tr[class*='+jQuery(this).val().split(" ").join("-")+']:not(.firmware-search, .file-table-head)').show();

			if (jQuery(this).val() == "") 	jQuery(this).parents('table').find('tr:not(.firmware-search, .file-table-head)').show();
		});
		//end search box

			jQuery('.section-head').append('<img src="/images/arrow-open.png" class="arrowOpen"><img src="/images/arrow-down.png" style="display:none;" class="arrowClose">');
			jQuery('.file_table, .section-children').slideUp(0); 
			jQuery('h4.typeTitle').click(function() { 
				if (jQuery(this).next('.file_table').css('display') == 'none') jQuery('.file_table').not(this).slideUp(); 
				jQuery(this).next('.file_table').slideToggle();
			});
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
	})
})(jQuery);