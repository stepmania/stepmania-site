(function($){

$(function(){
	// https://github.com/xoxco/rainbow-text
	$(".rainbow").rainbow({
		colors: [
			'#FF0000',
			'#f26522',
			'#fff200',
			'#00a651',
			'#28abe2',
			'#2e3192',
			'#6868ff'
		],
		animate: true,
		animateInterval: 200,
		pad: false,
		pauseLength: 100,
	});

	$("#Form_AdminFormFeatures").hide();
	$(".forum-admin-features h3").click(function(self) {
		$("#Form_AdminFormFeatures").slideToggle();
	});
});

})(jQuery);
