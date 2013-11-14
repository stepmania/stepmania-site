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

	var editor = $("textarea#Form_PostMessageForm_Content");
	// editor.ckeditor();

	$("#Form_PostMessageForm").submit(function(){
	//	if (CKEDITOR.instances.editor)
	//		CKEDITOR.instances.editor.destroy();
	//	editor.destroy();
	//return false;
	});

	$(".replyLink").click(function(){
		var post = $(this)
		var postData = {
			id: post.attr("x-post-id"),
			user: post.attr("x-post-author"),
			data: post.attr("x-post-data")
		}
		editor.val(
			editor.val() +
			"[quote=" + postData.user + "]" +
			postData.data +
			"[/quote]\n"
		);
		editor.focus();
		return false;
	});
});

})(jQuery);
