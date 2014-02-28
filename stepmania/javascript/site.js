(function($){$(function(){
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
		pauseLength: 100
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
        if (editor.length > 0) {
            var post = $(this);
            var postData = {
                id: post.attr("x-post-id"),
                user: post.attr("x-post-author"),
                data: post.attr("x-post-data")
            };
            editor.val(
                editor.val() +
                "[quote=" + postData.user + "]" +
                postData.data +
                "[/quote]\n"
            );
            editor.focus();
            return false;
        }
	});

    /* Forum stuff */
	/**
	 * Handle the OpenID information Box.
	 * It will open / hide the little popup
	 */

	// default to hiding the BBTags
	if($("#BBTagsHolder")) {
		$("#BBTagsHolder").hide().removeClass("showing");
	}

	$("#ShowOpenIDdesc").click(function() {
		if($("#OpenIDDescription").hasClass("showing")) {
			$("#OpenIDDescription").hide().removeClass("showing");
		} else {
			$("#OpenIDDescription").show().addClass("showing");
		}
		return false;
	});

	$("#HideOpenIDdesc").click(function() {
		$("#OpenIDDescription").hide();
		return false;
	});


	/**
	 * BBCode Tools
	 * While editing / replying to a post you can get a little popup
	 * with all the BBCode tags
	 */
	$("#BBCodeHint").click(function() {
		if($("#BBTagsHolder").hasClass("showing")) {
			$(this).text("View Formatting Help");
			$("#BBTagsHolder").hide().removeClass("showing");
		} else {
			$(this).text("Hide Formatting Help");
			$("#BBTagsHolder").show().addClass("showing");
		}
		return false;
	});

	/** 
	 * MultiFile Uploader called on Reply and Edit Forms
	 */
	//$('#Form_PostMessageForm_Attachment').MultiFile({ namePattern: '$name-$i' });

	/**
	 * Delete post Link.
	 *
	 * Add a popup to make sure user actually wants to do 
	 * the dirty and remove that wonderful post
	 */

	$('.postModifiers a.deletelink').click(function(){
		var link = $(this);
		var first = $(this).hasClass('firstPost');
		
		if(first) {
			if(!confirm("Are you sure you wish to delete this thread?\nNote: This will delete ALL posts in this thread.")) return false;
		} else {
			if(!confirm("Are you sure you wish to delete this post?")) return false;
		}

		$.post($(this).attr("href"), function(data) {
			if(first) {
				// if this is the first post then we have removed the entire thread and therefore
				// need to redirect the user to the parent page. To get to the parent page we convert
				// something similar to general-discussion/show/1 to general-discussion/
				var url = window.location.href;
				
				var pos = url.lastIndexOf('/show');
				
				if(pos > 0) window.location = url.substring(0, pos);
			}
			else {
				// deleting a single post. 
				link.parents(".singlePost").fadeOut();
			}
		});

		return false;
	});

	/**
	 * Mark Post as Spam Link.
	 * It needs to warn the user that the post will be deleted
	 */
	$('.postModifiers a.markAsSpamLink').click(function(){
		var link = $(this);
		var first = $(this).hasClass('firstPost');
		
		if(!confirm("Are you sure you wish to mark this post as spam? This will remove the post, and suspend the user account")) return false;
		
		$.post($(this).attr("href"), function(data) {
			if(first) {
				// if this is the first post then we have removed the entire thread and therefore
				// need to redirect the user to the parent page. To get to the parent page we convert
				// something similar to general-discussion/show/1 to general-discussion/
				var url = window.location.href;
				
				var pos = url.lastIndexOf('/show');
				
				if(pos > 0) window.location = url.substring(0, pos);
			}
			else {
				window.location.reload(true);
			}
		});
	
		return false;
	});
	
	/**
	 * Delete an Attachment via AJAX
	 */
	$('a.deleteAttachment').click(function() {
		if(!confirm("Are you sure you wish to delete this attachment")) return false;
		var id = $(this).attr("rel");
	
		$.post($(this).attr("href"), function(data) {
			$("#CurrentAttachments li.attachment-"+id).fadeOut(); // hide the deleted attachment
		});
	
		return false;
	});

	/**
	 * Subscribe / Unsubscribe button
	 */
	$("td.replyButton a.subscribe").click(function() {
		$.post($(this).attr("href"), function(data) {
			if(data == 1) {
				$("td.replyButton a.subscribe").fadeOut().hide();
				$("td.replyButton a.unsubscribe").fadeIn();
			}
		});
		return false;
	});

	$("td.replyButton a.unsubscribe").click(function() {
		$.post($(this).attr("href"), function(data) {
			if(data == 1) {
				$("td.replyButton a.unsubscribe").fadeOut().hide();
				$("td.replyButton a.subscribe").fadeIn();
			}
		});
		return false;
	});
})})(jQuery);
