var mylat, mylong, i, 
vLanguage = [{
	'manager':'On Service Manager',
	'warranty':'Make Warranty Claims',
	'oem':'OEM'
}],
vLocations =  [
	{
		'industry':34,
		'name':'AECO Sales and Service',
		'address':'619 North Broadway, Tecumseh, OK 74873',
		'phone':['800-682-0358','405-598-2915'],
		'fax':'405-598-5506',
		'email': 'eddy_AECO@ionet.net',
		'web' : 'www.aecosales.com',
		'location':[35.2522046,-96.936734],
		'options':[vLanguage[0]['manager']]
	},
	{
		'industry':4,
		'name':'AECO Sales and Service',
		'address':'10290 Monroe Drive #206, Dallas, TX 75229',
		'phone':['214-352-4755'],
		'fax':'214-352-8154',
		'email': 'eddy_AECO@ionet.net',
		'web' : 'www.aecosales.com',
		'location':[32.886013,-96.878325],
		'options':[]
	},
	{
		'industry':4,
		'name':'Arbortronics, Inc',
		'address':'1520 Lodestar Road, Unit 20, Downsview, Ontario, Canada M3J 3C1',
		'phone':['416-638-6112'],
		'fax':'416-638-0007',
		'email': 'support@arbortronics.com',
		'web' : 'www.arbortronics.com',
		'location':[43.761552,-79.467824],
		'options':[vLanguage[0]['manager']]
	},
	{
		'industry':4,
		'name':'BCP Systems',
		'address':'1560 S. Sinclair, Anaheim, CA 92806',
		'phone':['714-572-9288'],
		'fax':'714-572-0517',
		'email': '',
		'web' : '',
		'location':[33.808355,-117.880608],
		'options':[]
	},
	{
		'industry':4,
		'name':'Betson Baltimore Distributing',
		'address':'6615 Tributary St., Suite F, Baltimore, MD 21224',
		'phone':['800-296-4100'],
		'fax':'410-646-2053',
		'email': 'rzayasbazan@betson.com',
		'web' : 'www.betson.com',
		'location':[39.267832,-76.530049],
		'options':[]
	},
	{
		'industry':34,
		'name':'Betson Enterprises',
		'address':'303 Paterson Plank Road, Carlstadt, NJ 07072',
		'phone':['800-524-2343'],
		'fax':'201-438-6950',
		'email': 'rzayasbazan@betson.com',
		'web' : 'www.betson.com',
		'location':[40.828433,-74.080717],
		'options':[vLanguage[0]['manager'],vLanguage[0]['warranty']]
	},		
	{
		'industry':4,
		'name':'Betson Mid-West',
		'address':'1158 Tower Lane, Bensenville, IL 60106',
		'phone':['800-386-7040', '630-238-9400'],
		'fax':'630-238-9409',
		'email': 'rzayasbazan@betson.com',
		'web' : 'www.betson.com',
		'location':[41.987399,-87.961414],
		'options':[]
	},
	{	
		'industry':4,
		'name':'Betson Philadelphia',
		'address':'310 Hansen Access Road #105, King of Prussia, PA 19406',
		'phone':['800-523-8766'],
		'fax':'610-265-0909',
		'email': 'rzayasbazan@betson.com',
		'web' : 'www.betson.com',
		'location':[40.085735,-75.372569],
		'options':[]
	},	
	{	
		'industry':4,
		'name':'Betson Pittsburgh',
		'address':'2707 W. Carson Street, Pittsburgh, PA 15204',
		'phone':['800-524-2343'],
		'fax':'412-331-0712',
		'email': 'rzayasbazan@betson.com',
		'web' : 'www.betson.com',
		'location':[40.457705,-80.045357],
		'options':[]
	},	
	{	
		'industry':4,
		'name':'Betson Texas',
		'address':'1127 Conveyor Lane, Dallas, TX 75247',
		'phone':['800-524-2343', '214-638-4900'],
		'fax':'214-638-4925',
		'email': 'rzayasbazan@betson.com',
		'web' : 'www.betson.com',
		'location':[32.803492,-96.856432],
		'options':[]
	},	
	{	
		'industry':4,
		'name':'Betson West - Hayward',
		'address':'3470 Depot Road, Hayward, CA 94545',
		'phone':['800-526-7906', '510-293-9127'],
		'fax':'510-785-5317',
		'email': 'rzayasbazan@betson.com',
		'web' : 'www.betson.com',
		'location':[37.636826,-122.126906],
		'options':[vLanguage[0]['manager'],vLanguage[0]['warranty']]
	},		
	{	
		'industry':4,
		'name':'Betson West- Buena Park',
		'address':'5660 Knott Avenue, Buena Park, CA 90621',
		'phone':['714-228-7500', '800-824-6596'],
		'fax':'714-228-7510',
		'email': 'rzayasbazan@betson.com',
		'web' : 'www.betson.com',
		'location':[33.876872,-118.010623],
		'options':[]
	},	
	{	
		'industry':4,
		'name':'Changer Services, Inc.',
		'address':'2339 Waters Drive, Mendota Heights, MN 55120',
		'phone':['651-405-0959', '888-328-5067'],
		'fax':'651-405-0947',
		'email': 'mstolley@changerservices.com',
		'web' : 'www.changerservices.com',
		'location':[44.870859,-93.161182],
		'options':[vLanguage[0]['manager'],vLanguage[0]['warranty']]
	},	
	{	
		'industry':4,
		'name':'Dieb Enterprises',
		'address':'9334 Hwy. Bb, Hillsboro, MO 63050',
		'phone':['800-237-0521', '636-789-4593'],
		'fax':'636-789-5848',
		'email': 'dieb@diebent.com',
		'web' : 'www.diebent.com',
		'location':[38.276555,-90.592611],
		'options':[]
	},
	{	
		'industry':4,
		'name':'Diversified Equipment Sourcing, Inc.',
		'address':'950-23 Blanding Blvd., Suite 332, Orange Park, FL 32065',
		'phone':['904-334-6684'],
		'fax':'904-688-0523',
		'email': 'kmcdowall@diverseq.com',
		'web' : 'www.diverseq.com',
		'location':[30.138968,-81.774576],
		'options':[]
	},	
	{	
		'industry':4,
		'name':'Eastern Commercial Services',
		'address':'813-A Professional Place, Suite 106, Chesapeake, VA 23320',
		'phone':['800-486-1020'],
		'fax':'757-547-4772',
		'email': 'bob@easterncommercial.com',
		'web' : 'www.easterncommercial.com',
		'location':[36.761179,-76.234286],
		'options':[vLanguage[0]['manager'],vLanguage[0]['warranty']]
	},	
	{	
		'industry':4,
		'name':'Ellenby Technologies, Inc.',
		'address':'412 Grandview Ave., Woodbury Heights, NJ 08097',
		'phone':['856-848-2020'],
		'fax':'856-848-7080',
		'email': 'bob.dobbins@ellenbytech.com',
		'web' : 'www.ellenbytech.com',
		'location':[39.811054,-75.144183],
		'options':[vLanguage[0]['manager'],vLanguage[0]['oem']]
	},	
	{	
		'industry':4,
		'name':'Gekay Sales and Service',
		'address':'15 Dana Way, Ludlow, MA 01056',
		'phone':['800-832-0028', '413-583-7720'],
		'fax':'413-583-7746',
		'email': 'sales@gekay.com',
		'web' : 'www.gekay.com',
		'location':[42.182016,-72.515018],
		'options':[vLanguage[0]['manager']]
	},	
	{	
		'industry':4,
		'name':'LYF Ingenieria',
		'address':'Carretera a La Colorada, Km 4.5 Colonial Parque Industrial, Hermosillo, Sonora, Mexico ZC 83299',
		'phone':['52-55-5370-1138'],
		'fax':'52-55-5370-1120',
		'email': 'Benny@lopez-fernandez.com',
		'web' : '',
		'location':[30.889354,-108.188584],
		'options':[vLanguage[0]['manager']]
	},	
	{	
		'industry':3,
		'name':'Modern Gaming, Inc.',
		'address':'20415 Highway 16, Denham Springs, LA 70726',
		'phone':['225-698-6800'],
		'fax':'225-698-6833',
		'email': 'cassie@moderngaminginc.com',
		'web' : '',
		'location':[30.368689,-90.86586],
		'options':[vLanguage[0]['manager'],vLanguage[0]['warranty']]
	},	
	{	
		'industry':34,
		'name':'Permaquim, S.A.',
		'address':'Martin J. Haedo, 3615 Florida, Buenos Aires, Argentina',
		'phone':['0054-11-4760-3000'],
		'fax':'',
		'email': 'ventas@permaquim.com',
		'web' : 'www.permaquim.com',
		'location':[-34.540911,-58.506498],
		'options':[vLanguage[0]['manager']]
	},	
	{	
		'industry':4,
		'name':'Serv-A-Mech Electronics, Inc.',
		'address':'5916 West 34th Street #B, Houston, TX 77092',
		'phone':['800-323-7214'],
		'fax':'713-681-8570',
		'email': 'sameinc@aol.com',
		'web' : '',
		'location':[29.819359,-95.476331],
		'options':[vLanguage[0]['manager']]
	},	
	{	
		'industry':4,
		'name':'Southeastern Vending Services',
		'address':'1328-B Cross Beam Drive, Charlotte, NC 28217',
		'phone':['800-825-8555', '704-394-4911'],
		'fax':'704-394-3789',
		'email': 'jon@vendingsvs.com',
		'web' : 'www.vendingsvs.com',
		'location':[35.180002,-80.921313],
		'options':[vLanguage[0]['manager']]
	},	
	{	
		'industry':34,
		'name':'Suzo- Happ Group',
		'address':'2015 Helm Drive, Las Vegas, NV 89119',
		'phone':['702-597-4840', '888-289-4277'],
		'fax':'702-597-4837',
		'email': 'James.Packer@suzohapp.com',
		'web' : 'www.suzohapp.com',
		'location':[36.067624,-115.124892],
		'options':[]
	},	
	{	
		'industry':34,
		'name':'Suzo- Happ Group',
		'address':'112 Ashford Ave, Suite 2B, San Juan, Puerto Rico 00907',
		'phone':['787-724-4840'],
		'fax':'787-721-4837',
		'email': 'James.Packer@suzohapp.com',
		'web' : 'www.suzohapp.com',
		'location':[18.457572,-66.075145],
		'options':[vLanguage[0]['manager'],vLanguage[0]['warranty']]
	},
	{	
		'industry':34,
		'name':'Suzo- Happ Group',
		'address':'1743 Linneman Rd, Mount Prospect, IL 60056',
		'phone':['888-289-4277'],
		'fax':'800-593-4277',
		'email': 'James.Packer@suzohapp.com',
		'web' : 'www.suzohapp.com',
		'location':[42.033834,-87.949699],
		'options':[]
	},	
	{	
		'industry':4,
		'name':'Team One Repair, Inc.',
		'address':'150 N. Mill Creek Road, Suite G, Quincy, CA 95971',
		'phone':['530-283-3736', '800-873-4323'],
		'fax':'530-283-3122',
		'email': 'mei@teamonerepair.com',
		'web' : 'www.teamonerepair.com',
		'location':[39.937462,-120.90478],
		'options':[vLanguage[0]['manager']]
	},	
	{	
		'industry':4,
		'name':'Vendor\'s Repair Service, Inc.',
		'address':'6025 Cinderlane Parkway, Orlando, FL 32810',
		'phone':['407-291-1712'],
		'fax':'407-578-0651',
		'email': 'sales@vendorsrepair.com',
		'web' : 'www.vendorsrepair.com',
		'location':[28.611726,-81.425277],
		'options':[vLanguage[0]['manager']]
	}
];
jQuery(document).ready(function() {
	if (typeof(Number.prototype.toRad) === "undefined") {
	  Number.prototype.toRad = function() {
	    return this * Math.PI / 180;
	  }
	}

	if ("geolocation" in navigator)  distanceSetUp(); else  compatDistanceSetup();

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
		alert('enter your address');
	}

	function output() { 
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
			jQuery('#service-centers').append('<div id="more-than-5"></div><a id="show-more-centers" onclick="showMoreSC()">Show More</a>');
			while(i<vLocations.length) {
				nicelyShowCenters(1);
				i++;
			}

		}
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


});
function nicelyShowCenters(maxed) {
	maxed = (maxed === 1) ?  jQuery('#more-than-5') :  jQuery('#service-centers');
	maxed.append('<div id="service-centers"> <div class="service-center" > <div class="scnumber">'+(i+1)+'</div> <div class="scinformation" itemscope itemtype="http://schema.org/LocalBusiness"> <div> <span class="center-heading">Name:</span> <span itemprop="name">'+vLocations[i]['name']+'</span> </div> <div> <span class="center-heading">Address:</span> <span itemprop="address">'+vLocations[i]['address']+'</span> </div> <div> <span class="center-heading">Phone:</span> <span>'+showPhoneNumber()+'</span> </div> <div> <span class="center-heading">Fax:</span> <span itemprop="faxNumber">'+vLocations[i]['fax']+'</span> </div> <div> <span class="center-heading">Email:</span> <span> <a href="mailto:'+vLocations[i]['email']+'" itemprop="email">'+vLocations[i]['email']+'</a> </span> </div> <div> <span class="center-heading">Web:</span> <span> <a href="http://'+vLocations[i]['web']+'" target="_blank" itemprop="url">'+vLocations[i]['web']+'</a> </span> </div> </div> <div style="clear:both;"></div> </div> </div>');
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
	var ntext = (jQuery('#show-more-centers').text() =='Show More') ? 'Show Less':'Show More';
	jQuery('#show-more-centers').text(ntext);
	return false;
}
// gaming 3
// retail 4
// transportation 7
// vending 8