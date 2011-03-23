$(function(){
	$(".webFancy").fancybox({
		'width' : '75%',
		'height' : '75%',
		'autoScale' : false,
		'transitionIn' : 'elastic',
		'transitionOut' : 'elastic',
		'type' : 'iframe',
		'centerOnScroll' : true
	});
	$(".web40Fancy").fancybox({
		'autoDimensions': false,
		'width': 400,
		'height' : 200,
		'padding' : 0,
		'scrolling': 'no',
		'centerOnScroll' : true,
		'type' : 'iframe'
	});
	$(".web400x300Fancy").fancybox({
		'autoDimensions': false,
		'width': 400,
		'height' : 300,
		'padding' : 0,
		'scrolling': 'no',
		'centerOnScroll' : true,
		'type' : 'iframe'
	});
	$(".imageFancy").fancybox({
		'titleShow' : false,
		'transitionIn' : 'elastic',
		'transitionOut' : 'elastic',
		'autoScale' : true,
		'centerOnScroll' : true
 	});
 	
 	$("a[rel=image_gallery]").fancybox({
		'transitionIn'		: 'elastic',
		'transitionOut'		: 'elastic',
		'title':false,
		'centerOnScroll' : true
	});
	
	$(".web500Fancy").fancybox({
		'autoDimensions': false,
		'width': 500,
		'height' : 400,
		'padding' : 0,
		'scrolling': 'no',
		'centerOnScroll' : true,
		'type' : 'iframe'
	});
	

})