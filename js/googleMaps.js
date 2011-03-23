var googleMaps = {
	map: null,
	geocoder:null,
	debug: function ( foo ) {
		window.console && console.log && console.log( foo );
	},
	createMarker : function ( obj, draggable ) {
		var m = new GMarker( obj.point, draggable ? { draggable: true } : '');
		if(obj.html){
			m.html = obj.html;
		}
		if(m.html){
			m.html = obj.html;
			GEvent.addListener( m, 'click', function() {
				GEvent.clearListeners( googleMaps.map, 'dragend' );
				m.openInfoWindowHtml( '<div class="bubble">' + m.html + '</div>' );
			} );
		}
		return m;
	},
	init: function (points) {
		if (	GBrowserIsCompatible( )) { 			
			googleMaps.map = new GMap2( document.getElementById( 'gMap' ));
			googleMaps.map.setCenter( new GLatLng( 0, 0 ), 0 );
			var bounds = new GLatLngBounds();
			googleMaps.geocoder = new GClientGeocoder();
			
			for(var i in points){
				if(points[i]['lon'] && points[i]['lat']){
				var p;
				//googleMaps.debug(points[i]);
				p = new GLatLng(points[i]['lat'], points[i]['lon']);
				bounds.extend(p);
				googleMaps.map.setCenter(p, 14);
				googleMaps.map.addOverlay(googleMaps.createMarker({point:p, html: '<' + 'h2>' + points[i]['html'] + '</' + 'h2>'}, false));
				}
				
			}
			googleMaps.map.setCenter( bounds.getCenter( ), googleMaps.map.getBoundsZoomLevel( bounds ) - 1 );
			
			
			//googleMaps.map.addControl( new GSmallMapControl());
			//googleMaps.map.addControl( new GMapTypeControl());
			
		}
	}
}

$(document).unload( function () { GUnload } );