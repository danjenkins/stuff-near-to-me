$(function(){

	$('a.userFavSelect').click(function(){
		//look at the class, figure out if its a favourite at the moment or not.
		//is it placeNotFavourited or placeFavourited 
		
		//pretty much ignore the href even though its there, create out own url
		if($(this).is('.placeNotFavourited')){
			var sendFav = '1';//its not already favourited, we're sending off a request to favourite it so send 1
			var class = ".placeNotFavourited";
		}else{
			var sendFav = '0';
			var class = ".placeFavourited";
		}
		
		var ajaxData = {placeId : $(this).attr('id').replace('userFavs', ''),favourite : sendFav };
		$('a.userFavSelect img').addClass('loading');
		$.ajax({
			url: "/process/userFavs",
			global: false,
			type: "GET",
			data: (ajaxData),
			dataType: "json",
			success: function(msg){
				if(msg.status != 'error'){
					//if its successful we need to remove that class of not or yes favourite and change it
					//change the image too
					if(sendFav == '1'){
						$('a.userFavSelect').removeClass('placeNotFavourited');
						$('a.userFavSelect').addClass('placeFavourited');
						$('a.userFavSelect img').attr('src', '/images/icons/star_48.png');
					}else{
						$('a.userFavSelect').removeClass('placeFavourited');
						$('a.userFavSelect').addClass('placeNotFavourited');
						$('a.userFavSelect img').attr('src', '/images/icons/star_off_48.png');
					}
				}else{
					//output error by alert
					alert('Sorry there has been an error updating your favourites\n please try again later');
				}
				$('a.userFavSelect img').removeClass('loading');
			},
			error: function(req, stat, err){
				alert('Sorry there has been an error updating your favourites\n please try again later');
				$('a.userFavSelect img').removeClass('loading');
			}
		});
		
		//return false so it doesnt go onto submit
		return false;
	})

})