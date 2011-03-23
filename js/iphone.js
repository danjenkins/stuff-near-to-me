/*
if (!window.iPhoneStuff) {
  iPhoneStuff = {};
}


iPhoneStuff.mobile = {};
iPhoneStuff.mobile.init = function(){
	window.scrollTo(0,1);
}

if(window.addEventListener){
  window.addEventListener('load', iPhoneStuff.mobile.init, false);
}else if(window.attachEvent){
  window.attachEvent('onload', iPhoneStuff.mobile.init);
}
*/


addEventListener("load", function(){ 
	setTimeout(function(){window.scrollTo(0, 1);}, 100);
}, false);

addEventListener("resize", function(){ 
	if(window.pageYOffset < 100){
		setTimeout(function(){window.scrollTo(0, 1);}, 100);
	}
}, false);

