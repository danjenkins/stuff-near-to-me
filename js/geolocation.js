function success(position) {
	if ($('#geoLocation').is('.success')) {
		//Firefox is hitting this twice for some reason! 
		return;
	}
	$('#geoLocation').addClass('success');
	$('.geo').text('Submitting');
	$('#geoLocation form input[name=lat]').val(position.coords.latitude);
	$('#geoLocation form input[name=lon]').val(position.coords.longitude);
	$('#geoLocation form').submit();
}
function error(msg) {
	var message = typeof msg == 'string' ? msg : "failed";
	$('#geoLocation span.feedback').html(message);
	$('#geoLocation').addClass('fail');
}

function successDir(position) {
	if ($('#geoLocationDirections form').is('.success')) {
		//Firefox is hitting this twice for some reason! 
		return;
	}
	$('#geoLocationDirections form').addClass('success');
	$('#geoLocationDirections form button').text('Submitting');
	$('#geoLocationDirections form input[name=saddr]').val(position.coords.latitude + ',' + position.coords.longitude);
	$('#geoLocationDirections form').submit();
}
function errorDir(msg) {
	var message = typeof msg == 'string' ? msg : "failed";
	$('#geoLocationDirections form button').html(message);
	$('#geoLocationDirections').addClass('fail');
}


$(function(){
	if (navigator.geolocation) {
		//add in a button saying, get current position
		$('#geoLocation').prepend('<h2>By your current location</h2>');
		$('#categoryContainer').show();//show a select box
		$('#geoLocation form').append('<button class="geo">Find places near your location</button>').find('button.geo').click(function(){
			$('.geo').text('Searching...');
			navigator.geolocation.getCurrentPosition(success, error);//needs to have a success and error functionality
			return false;
		})
		
		
		$('#geoLocationDirections').show();
		$('#geoLocationDirections form button').click(function(){
			$('#geoLocationDirections form button').text('Searching...');
			navigator.geolocation.getCurrentPosition(successDir, errorDir);//needs to have a success and error functionality
			return false;
		})
	}
})
