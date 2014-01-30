var mylat, mylong, i;
jQuery(document).ready(function() {
	if (typeof(Number.prototype.toRad) === "undefined") {
	  Number.prototype.toRad = function() {
	    return this * Math.PI / 180;
	  }
	}

	if ("geolocation" in navigator)  distanceSetUp(); else  compatDistanceSetup();
});
function distanceSetUp() {
		navigator.geolocation.getCurrentPosition(function(position) {
			mylat= position.coords.latitude, mylong = position.coords.longitude;
			output();
		},
		function() {
			compatDistanceSetup();
		});
	}

	function compatDistanceSetup() {
		jQuery('#loading').slideUp();
		jQuery('#service-centers').html('<h3 style="width:100%;text-align:center;font-weight:normal;">'+vLanguage[cvlang][2]['compat']+'</h3>');
	}
	function output() { 
		pagealert(vLanguage[cvlang][2]['success'],{'class':' fa-a fa-a-mapm','theme':'green','delay':3000});
		jQuery('#service-centers').empty();
		i =0;
		while( i<vLocations.length) {
			var closeness = calculateDistance(mylat,vLocations[i]['location'][0],mylong,vLocations[i]['location'][1]);
			vLocations[i]['closeness'] = closeness;
			i++;
		}
		i=0;
		vLocations.sort(function(a, b){ return a.closeness - b.closeness });
		while(i<vLocations.length && i<5) {
			nicelyShowCenters();
			i++;
		}
		if (i<vLocations.length) {
			jQuery('#service-centers').append('<div id="more-than-5"></div><a id="show-more-centers" onclick="showMoreSC()" class="bttn" style="display:inline-block;"><img src="/images/plus.png" width="10px" style="margin-bottom: 3px;"> '+vLanguage[cvlang][1]['more']+'</a>');
			while(i<vLocations.length) {
				nicelyShowCenters(1);
				i++;
			}

		}/*#hold-sc*/
		jQuery('#loading').slideUp();
	}

	function calculateDistance(lat1,lat2,lon1,lon2) {
		var R = 6371; // km
		var dLat = (lat2-lat1).toRad();
		var dLon = (lon2-lon1).toRad();
		var lat1 = lat1.toRad();
		var lat2 = lat2.toRad();

		var a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(lat1) * Math.cos(lat2); 
		var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
		var d = R * c;
		return d;
	}

function nicelyShowCenters(maxed) {
	maxed = (maxed === 1) ?  jQuery('#more-than-5') :  jQuery('#service-centers');
	var opts = (vLocations[i]['options'].length !== 0) ? showCenterOptions() : "";
	maxed.append('<div class="service-centers"> <div class="service-center" > <div class="scnumber">'+ (i+1) + opts +'</div> <div class="scinformation" itemscope itemtype="http://schema.org/LocalBusiness"> <div> <span class="center-heading">'+vLanguage[cvlang][1]['name']+':</span> <span itemprop="name">'+vLocations[i]['name']+'</span> </div> <div> <span class="center-heading">'+vLanguage[cvlang][1]['address']+':</span> <span itemprop="address">'+vLocations[i]['address']+'</span> </div> <div> <span class="center-heading">'+vLanguage[cvlang][1]['phone']+':</span> <span>'+showPhoneNumber()+'</span> </div> <div> <span class="center-heading">'+vLanguage[cvlang][1]['fax']+':</span> <span itemprop="faxNumber">'+vLocations[i]['fax']+'</span> </div> <div> <span class="center-heading">'+vLanguage[cvlang][1]['email']+':</span> <span> <a href="mailto:'+vLocations[i]['email']+'" itemprop="email">'+vLocations[i]['email']+'</a> </span> </div> <div> <span class="center-heading">'+vLanguage[cvlang][1]['web']+':</span> <span> <a href="http://'+vLocations[i]['web']+'" target="_blank" itemprop="url">'+vLocations[i]['web']+'</a> </span> </div> </div> <div class="google-map"> <a href="'+vLocations[i]['map']+'" target="_blank" title="'+vLanguage[cvlang][1]['map']+'"> <img src="/images/service-centers/gmap-'+vLocations[i]['id']+'.jpg"></a> </div> <div style="clear:both;"></div> </div> </div> ');
}
function showCenterOptions() {
	var opts = "";
	if (typeof vLocations[i]['options']['manager'] !== 'undefined') opts+= "<img src='/images/service-centers/manager-ico.png' title='"+vLanguage[cvlang][0]['manager']+"' >";
	if (typeof vLocations[i]['options']['oem'] !== 'undefined') opts+= "<img src='/images/service-centers/oem-ico.png' title='"+vLanguage[cvlang][0]['oem']+"' >";
	if (typeof vLocations[i]['options']['warranty'] !== 'undefined') opts+= "<img src='/images/service-centers/warranty-ico.png' title='"+vLanguage[cvlang][0]['warranty']+"' >";	
	return "<span>"+opts+"</span>";
}
function showPhoneNumber() {
	var vphones = "";
	vLocations[i]['phone'].forEach(function(index) {
		vphones += (vphones == "") ? '<a href="tel:'+index+'" itemprop="telephone">'+index+'</a>' : ', <a href="tel:'+index+'" itemprop="telephone">'+index+'</a>';
	});
	return vphones;
}
function showMoreSC() {
	jQuery('#more-than-5').slideToggle();
	if (jQuery('#show-more-centers').attr('state') != "0") {
		jQuery('#show-more-centers').html('<img src="/images/minus.png" width="10px" style="margin-bottom: 3px;"> '+vLanguage[cvlang][1]['less']);
		jQuery('#show-more-centers').attr('state','0');
	}
	else {
		jQuery('#show-more-centers').html('<img src="/images/plus.png" width="10px" style="margin-bottom: 3px;"> '+vLanguage[cvlang][1]['more']);
		jQuery('#show-more-centers').attr('state','1');
	}
	return false;
}

function queryAddress(address) {
		if (address === 'undefined') return;
		else address = address.replace(' ', '+');
		jQuery.ajax({
			url: 'http://maps.googleapis.com/maps/api/geocode/json?address='+address+'&sensor=false',
			dataType: 'json',
			success: function(data) {
				var tempsave = data;
				console.log(tempsave);
				if (tempsave['status'] === "OK") {
					mylong = tempsave['results'][0]['geometry']['location']['lng'], mylat = tempsave['results'][0]['geometry']['location']['lat'];
					output();

				} else pagealert(vLanguage[cvlang][2]['cant-locate'],{'class':' fa-a fa-a-exclamation','theme':'red','delay':5000});
			},
			error: function() {
				pagealert(vLanguage[cvlang][2]['cant-locate'],{'class':' fa-a fa-a-exclamation','theme':'red','delay':5000});
			}
		});
	}
function locateViaForm() {
	if (jQuery('#service-center-form #address').val().length > 1) queryAddress(jQuery('#service-center-form #address').val());
	else pagealert(vLanguage[cvlang][2]['empty'],{'class':' fa-a fa-a-exclamation','theme':'red','delay':5000});
	//service-centers
}