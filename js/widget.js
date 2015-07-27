(function ($) {
	"use strict";
	$(function () {
		$('.player').click(function (){
			var s = $(this).siblings('.sample-player');			
			if($(this).hasClass('play')){
				$(this).removeClass( 'play');
				$(this).addClass( 'pause' );
				$(this).siblings('.sample-player')[0].play();
			}
			else{
				$(this).removeClass( 'pause');
				$(this).addClass( 'play' );  				
  				$(this).siblings('.sample-player')[0].pause();			
  			}
		});
	});
}(jQuery));