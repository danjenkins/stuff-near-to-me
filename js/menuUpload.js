$(function(){
	
	var parts = window.location.search.substr(1).split("&");
	var urlParts = {};
	for (var i = 0; i < parts.length; i++) {
    	var temp = parts[i].split("=");
    	urlParts[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
	}

	//console.log(urlParts);
	//console.log(urlParts.placeId);
	var btnUpload = $('#upload');
	var status = $('#status');
	new AjaxUpload(btnUpload, {
		action: '/process/menuUpload',
		//Name of the file input box
		name: 'uploadfile',
		onSubmit: function(file, ext){
			if (! (ext && /^(jpg|png|jpeg|gif|pdf)$/.test(ext))){
                  // check for valid file extension
				status.text('Only JPG, PNG, GIF or PDF files are allowed');
				return false;
			}
			status.text('Uploading...');
		},
		data :{
			placeId : urlParts.placeId,
			addedBy : 'webGuest'//try and get the name of the person logged in
		},
		responseType : 'json',
		onComplete: function(file, response){
			//On completion clear the status
			status.text('');
			$('#menuFileUpload, #menuLinks').empty();
			if(response.status==="success"){
				$('#menuFileUpload').append('<span>Thanks for that, we\'ve got to approve this now</span>');	
			}else{
				$('#menuFileUpload').append('<span>Sorry, theres been an error, try again later</span>');
			}
		}
	});
	
	$('#uploadMenuImage').click(function(){
		$('#menuUrlLink').hide();
		$('#menuFileUpload').show();
		$('#submitMenuLink').removeClass('selected');
		$(this).addClass('selected');
	})
	$('#submitMenuLink').click(function(){
		$('#menuFileUpload').hide();
		$('#menuUrlLink').show();
		$('#uploadMenuImage').removeClass('selected');
		$(this).addClass('selected');
	})
	
	
	
	
	$('#menuLocationForm').submit(function(){
		$.ajax({
			url: "/process/menuUrl",
			global: false,
			type: "POST",
			data: ({url : $('input[name=menuLocation]').val(),placeId : urlParts.placeId, addedBy : 'webGuest' }),
			dataType: "json",
			success: function(msg){
				$('#menuUrlLink, #menuLinks').empty();
				if(msg.status == 'success'){
					$('#menuUrlLink').append('<span>Thanks for that, we\'ve got to approve this now</span>');
				}else{
					$('#menuUrlLink').append('<span>Sorry, theres been an error, try again later</span>');
				}
				//console.log(msg)			
			},
			error: function(req, stat, err){
				$('#menuUrlLink, #menuLinks').empty();
				$('#menuUrlLink').append('<span>Sorry, theres been an error, try again later</span>');
				//console.log(req);
				//console.log(stat);
				//sconsole.log(err);
			}
		});
		return false;
	})
})