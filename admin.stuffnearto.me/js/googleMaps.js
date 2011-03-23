
var googleMaps = {
	map: null,
	geocoder:null,
	debug: function ( foo ) {
		window.console && console.log && console.log( foo );
	},
	createMarker : function ( obj, draggable ) {
		var m = new GMarker( obj.point, draggable ? { draggable: true } : '');
		if(m.html){
			m.html = obj.html;
			GEvent.addListener( m, 'click', function() {
				GEvent.clearListeners( googleMaps.map, 'dragend' );
				m.openInfoWindowHtml( '<div class="bubble">' + m.html + '</div>' );
			} );
		}
		GEvent.addListener( m, 'dragstart', function() { googleMaps.map.closeInfoWindow(); } );
		GEvent.addListener( m, 'dragend', function() {
			var p = m.getPoint();
			googleMaps.adminLatLon(p);
		} );
		return m;
	},
	getLatLonFromPostCode :function(postcode){
		//to go back in once its up on a server as I dont think google local works on a local machine even if you have the domain
		//var localSearch = new GlocalSearch();
		//googleMaps.debug(localSearch);
		//localSearch.setSearchCompleteCallback(null, function() {
			//alert('Here');
			//if (localSearch.results[0]) {    
				//var p = new GLatLng(localSearch.results[0].lat,localSearch.results[0].lng);
        		//googleMaps.map.setCenter(p, 13);
				//googleMaps.map.addOverlay( googleMaps.createMarker( { point: p, html: address }, true ));
     		//}else{
        		//alert("Postcode not found!");
      		//}
		//});  
    	//localSearch.execute(postcode + ", UK");
    	googleMaps.getLatLonFromAddress(postcode + ', UK');
	},
	getLatLonFromAddress : function (address) {
		if (googleMaps.geocoder) {
			googleMaps.geocoder.getLatLng(address, function(p) {
				if (!p) {
					alert(address + " not found");
	            }else {
	            	googleMaps.adminLatLon(p);
					googleMaps.map.setCenter(p, 13);
					googleMaps.map.addOverlay( googleMaps.createMarker( { point: p, html: address }, true ));
				}
			});
		}
	},
	getFullAddressInfo : function(address){
		if(googleMaps.geocoder){
			googleMaps.geocoder.getLocations(address, function(response){
				googleMaps.map.clearOverlays();
				if (!response || response.Status.code != 200) {
					alert("Sorry, we were unable to geocode that address");
				} else {
					googleMaps.debug(response);
					place = response.Placemark[0];
					p = new GLatLng(place.Point.coordinates[1], place.Point.coordinates[0]);
					var bubbleHtml = place.address + '<br>' + '<b>Country code:</b> ' + place.AddressDetails.Country.CountryNameCode;
					googleMaps.map.setCenter(p, 13);
					googleMaps.map.addOverlay( googleMaps.createMarker( { point: p, html: bubbleHtml }, true ));
				}
			});
		}
	},
	adminLatLon : function (p){
		if($('.latitudeAdmin').length){
			$('.latitudeAdmin').val(p.y);
		}
		if($('.longitudeAdmin').length){
			$('.longitudeAdmin').val(p.x);
		}
	},
	init: function (points) {
		if (	GBrowserIsCompatible( )) { 			
			googleMaps.map = new GMap2( document.getElementById( 'gMap' ));
			googleMaps.map.setCenter( new GLatLng( 0, 0 ), 0 );
			var bounds = new GLatLngBounds();
			googleMaps.geocoder = new GClientGeocoder();
			
			var p;
			
			if($('.latitudeAdmin').length && $('.longitudeAdmin').length){
				if($('.latitudeAdmin').val() != '' && $('.longitudeAdmin').val() != ''){
					p = new GLatLng($('.latitudeAdmin').val(), $('.longitudeAdmin').val());
					bounds.extend(p);
					googleMaps.map.setCenter(p, 13);
					googleMaps.map.addOverlay(googleMaps.createMarker({point:p}, true));
				}
			}else{
			//going to check what type points is, if array then loop through it, if object then get points out of obj
			//foreach()
			//p = new GLatLng( 57.2006, -2.2133 );
			//bounds.extend( p );
			//googleMaps.map.addOverlay( googleMaps.createMarker( { point: p }, true ));
			//foreach();
			
			//googleMaps.map.setCenter( bounds.getCenter( ), googleMaps.map.getBoundsZoomLevel( bounds ));
			}
			googleMaps.map.addControl( new GSmallMapControl());
			googleMaps.map.addControl( new GMapTypeControl());
			//googleMaps.map.setMapType(G_HYBRID_MAP);
			if($('input.postCodeAdmin').length){
				$('input.postCodeAdmin').after('<label>&nbsp;</label><button class="getLatLonFromPostCode">Get Lat Lon</button>');
				$('button.getLatLonFromPostCode').click(function(){
					googleMaps.getLatLonFromAddress($('input.postCodeAdmin').val());
					return false;
				})
			}
			
		}
	}
}

$(document).unload( function () { GUnload } );

    

