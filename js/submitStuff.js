var debug = function ( foo ) {
	window.console && console.log && console.log( foo );
}

$(function(){
	$(':input[name=categoryId]').change(function(){
		var class = $('option:selected',this).text().toLowerCase().replace(' ', '');
		
		var hideContainers = $('div.stuffContainer:not(.' + class + ', .none), fieldset.stuffContainer:not(.' + class + ', .none)', 'fieldset.formContainer');
		
		$(hideContainers).hide();
		$(':input', hideContainers).attr('disabled', 'disabled');
		
		var showContainers = $('div.' + class + ', fieldset.' + class + '', 'fieldset.formContainer');
		$(':input', showContainers).removeAttr('disabled');
		$(showContainers).show();	
	})
})