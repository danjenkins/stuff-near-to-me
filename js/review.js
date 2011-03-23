$(function(){
	
	var textareaText =  $('textarea[name=comment]').val();
	
	 $('textarea[name=comment]').focus(function(){
		if( $('textarea[name=comment]').val() == textareaText ){
			 $('textarea[name=comment]').val('');
		}
	})	
	
	$('form#review').submit(function(){
		var error = '';
		if($('textare[name=comment]').text() == textareaText){
			error += 'no comment';
		}	
		
		var ajaxData = {rating : $('input[name=rating]:checked').val(),type : $('input[name=type]').val(), id : $('input[name=id]').val(), comment : $('textarea[name=comment]').val() };
		
		$.ajax({
			url: "/process/submitReview",
			global: false,
			type: "POST",
			data: (ajaxData),
			dataType: "json",
			success: function(msg){
				if(msg.status != 'error'){
					$('form#review').slideUp();
					$('#submitReviewContainer').append('<div>Success, thanks for the review, it\'ll need to be approved</div>');
				}else{
					$('form#review').slideUp();
					$('#submitReviewContainer').append('<div>Sorry, theres been an error, please try again later</div>');
				}
			},
			error: function(req, stat, err){
				$('form#review').slideUp();
				$('#submitReviewContainer').append('<div>Sorry, theres been an error, please try again later</div>');
			}
		});
		return false;
	})
	
	$('form#placeReport').submit(function(){
		var error = '';
		if($('textarea[name=comment]').val()== textareaText){
			error += 'no comment';
		}	
		
		var ajaxData = {email: $('input[name=email]').val(),type : $('input[name=type]').val(), id : $('input[name=id]').val(), comment : $('textarea[name=comment]').val() };
		
		$.ajax({
			url: "/process/report",
			global: false,
			type: "POST",
			data: (ajaxData),
			dataType: "json",
			success: function(msg){
				if(msg.status != 'error'){
					$('form#placeReport').slideUp();
					$('#reportContainer').append('<div>Success, thanks for the comment, we\'ll look into it</div>');
				}else{
					$('form#placeReport').slideUp();
					$('#reportContainer').append('<div>Sorry, theres been an error, please try again later</div>');
				}
				//console.log(msg);
			},
			error: function(req, stat, err){
				$('form#placeReport').slideUp();
				$('#reportContainer').append('<div>Sorry, theres been an error, please try again later</div>');
			}
		});
		return false;
	})
	
	$('form#contactUs').submit(function(){
		var error = '';
		if($('textarea[name=comment]').val()== textareaText){
			error += 'no comment';
		}	
		
		var ajaxData = {email: $('input[name=email]').val(),type : $('input[name=type]').val(), id : $('input[name=id]').val(), comment : $('textarea[name=comment]').val() };
		
		$.ajax({
			url: "/process/report",
			global: false,
			type: "POST",
			data: (ajaxData),
			dataType: "json",
			success: function(msg){
				if(msg.status != 'error'){
					$('form#contactUs').slideUp();
					$('#contactContainer').append('<div>Success, thanks for contacting us</div>');
				}else{
					$('form#contactUs').slideUp();
					$('#contactContainer').append('<div>Sorry, theres been an error, please try again later</div>');
				}
				//console.log(msg);
			},
			error: function(req, stat, err){
				$('form#contactUs').slideUp();
				$('#contactContainer').append('<div>Sorry, theres been an error, please try again later</div>');
			}
		});
		return false;
	})
})