$(document).ready(function(){
 
	$(window).scroll(function() {
	  console.log($(window).scrollTop());
	  if ($(window).scrollTop() > 140) {
		  $('.menu').addClass('barraFlotante');
		  $('.menu').removeClass('barraNormal');  
	  }
	  else
	  {
		  $('.menu').removeClass('barraFlotante');  
		  $('.menu').addClass('barraNormal');
		}
	});

});
