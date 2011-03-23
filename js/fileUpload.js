var fancy = function(){
	$("a[rel=approved_list]").fancybox({
		'transitionIn'		: 'elastic',
		'transitionOut'		: 'elastic',
		'titlePosition' 	: 'outside',
		'titleFormat'       : function(title, currentArray, currentIndex, currentOpts) {
			return '<span id="fancybox-title-wrap"><span id="fancybox-title-left"></span><span id="fancybox-title-main">' + title + ' - Image ' +  (currentIndex + 1) + ' / ' + currentArray.length + '</span><span id="fancybox-title-right"></span></span>';
		}
	});
}

$(function(){

	var parts = window.location.search.substr(1).split("&");
	var urlParts = {};
	for (var i = 0; i < parts.length; i++) {
    	var temp = parts[i].split("=");
    	urlParts[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
	}
	
	if(urlParts.placeId){
		var typeVar = 'place';
		var idVar = urlParts.placeId;
	}else if(urlParts.categoryId){
		var typeVar = 'category';
		var idVar = urlParts.categoryId;
	}else if(urlParts.locationId){
		var typeVar = 'location';
		var idVar = urlParts.locationId;
	}


	var btnUpload = $('#upload');
	var status = $('#status');
	new AjaxUpload(btnUpload, {
		action: '/process/newImage',
		//Name of the file input box
		name: 'uploadfile',
		onSubmit: function(file, ext){
			if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
                  // check for valid file extension
				status.text('Only JPG, PNG or GIF files are allowed');
				return false;
			}
			status.text('Uploading...');
		},
		data :{
			type : typeVar,
			id : idVar,
			addedBy : 'webGuest'//try and get the name of the person logged in
		},
		responseType : 'json',
		onComplete: function(file, response){
			//On completion clear the status
			status.text('');
			//Add uploaded file to list
			if(response.status==="success"){
				$('<div class="imageContInfo" id="cont_' + response.imageId + '"></div>').appendTo('#files').html('<img src="' + response.imageLocation + '" alt="" /><form action="/process/imageInfo" method="post" class="imageExtraForm" id="form_' + response.imageId + '"><input type="hidden" name="imageId" value="' + response.imageId + '"/><label>Image Title</label><input type="text" name="imageTitle" /><br /><label>Alternate Text</label><input type="text" name="imageAlternateText" /><br /><label>Tags</label><input type="text" name="tags" /><br /><button>Submit</button></form><br />').addClass('success').find('#form_' + response.imageId).submit(function(){
						var form = $('form#form_' + response.imageId);
						$.ajax({
							url: "/process/imageInfo",
							global: false,
							type: "POST",
							data: ({imageId : response.imageId, imageTitle : $('input[name=imageTitle]', form).val(), imageAlternateText : $('input[name=imageAlternateText]', form).val(), tags : $('input[name=tags]', form).val()}),
							dataType: "json",
							success: function(msg){
								$(form).slideUp().remove();
								if(msg.status == 'success'){
									$('#cont_' + response.imageId).append('<p>Success, thanks alot</p>');
								}else{
									$('#cont_' + response.imageId).append('<p>Sorry, there has been an error, thanks for trying</p>');
								}
								$('<li style="display:none;" id="' + response.imageId + '"><a rel="approved_list" href="' + response.imageLocation + '" title="' + $('input[name=imgeTitle]',form).val() + '"><img src="' + response.imageLocation + '" alt="' + $('input[name=imageAlternateText]',form).val() + '" width="150px"/></a></li>').appendTo('#approvedImageList');
								setTimeout("$('#cont_' + " + response.imageId + ").fadeOut().remove()",1000);
								$('li#' + response.imageId).fadeIn();
								//$('#cont_' + response.imageId).remove();
								fancy();
								
							},
							error: function(req, stat, err){
							
							}
						});
					return false;
				});
			} else{
				$('<li></li>').appendTo('#files').text(file).addClass('error');
			}
		}
	});
	
	fancy();
			
});