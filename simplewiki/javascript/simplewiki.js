var controllerurl = location.pathname.replace(/edit$/, '').replace(/edit\/$/, '');

(function ($) {
	$().ready(function () {
		// find the save button and add in its accesskey... really need to
		// get around to patching this in core!
		$('#Form_StatusForm_action_startediting').attr('accesskey', 's');

		// add the lock update ping
		var timeout = $('#Form_EditForm_LockLength').val() ? $('#Form_EditForm_LockLength').val() * 1000 : 300000;

		if (timeout) {
			setInterval(function () {
				var url = $('#Form_EditForm_LockUpdate').val();
				$.post(url);
			}, timeout);
		}

		$('#Form_EditForm_LockUpdate')

		// toggle the new page form
		var createPageForm = $('#Form_CreatePageForm').hide();
		$('#Form_EditForm_action_addpage_t').click(function () {
			createPageForm.toggle();
		    return false;
		});

		// deleting an existing page
		$('#Form_EditForm_action_delete').click(function () {
			if (!confirm("Are you sure you want to delete this page?")) {
				return false;
			}
			return true;
		});

		// manage the creation of a new page
		createPageForm.submit(function () {
			if (!$(this).find('[@name="NewPageName"]').val()) {
				$(this).find('[@name="NewPageName"]').css('border', '1px solid red');
				return false;
			}
		});
		
		
		// preview stuff
		
		var previewdiv = $('#editorPreview'); 
		
		//initial preview load
		var updateFlag = true;
		updatePreview();
		updateFlag = false;
		
		var t = setInterval(updatePreview,5000);
			
		$('#Form_EditForm_Content').keyup(function(){
			updateFlag = true;		
		});
		
		if($('.markitup').length > 0){
			previewdiv.hide();
			previewdiv.after('<div id="showPreview"><a href="#">show / hide preview</a></div>');
			updatePreview();
			
			$('#showPreview a').click(function(){
				previewdiv.toggle();
				$('#Form_EditForm_Content').focus();
				return false;
			});
		}
		
		function updatePreview(){
			if(updateFlag){
				$.post(
					previewdiv.attr('data-url'), 
					{ content: $('#Form_EditForm_Content').val() },
					function(data){
						if(data){
							if(previewdiv.html() != data){
								previewdiv.html(data);
								updateFlag = false;
							}
						}		
					}
				);
			}
		}
		
		
		
		
		// image dialog window
		
		window.simpleWikiImageDialog = function(editorType){
			$("#dialogContent").load(controllerurl + '/imagepicker').dialog({
		        title: "Insert an image",
				modal: true,
				autoOpen: true,
				height: 500,
				width: 500,
				open: function(event, ui){
					$('#ExistingImage').livequery(function(){
					 	$(this).hide()
					 });
				},
				buttons: {
					"Insert / upload image": function() {
						$this = $(this);
						type = $('#Type input:radio:checked').val();
						title = $('#Form_ImagePickerForm_Title').val();
						if(type == 'new'){
							if($('#Form_ImagePickerForm_NewImage').val().length > 0){
								$('#Form_ImagePickerForm').ajaxSubmit({ 
						        	dataType:  'json',
						        	url: controllerurl + '/imageupload',
						        	beforeSubmit: function(){
						        		$('#uploadingIcon').show();
						        	},
						        	success: function(data){
						        		// validate									
										if(data.error){
						        			$('#uploadingIcon').hide();
						        			$('#Form_ImagePickerForm_NewImage').val('');
						        			alert(data.text);
						        			return false;
										}else{
											insertImageIntoEditor(data.link, title, editorType);
											$this.dialog( "close" );
											$('#Form_EditForm_Content').focus();
											updateFlag = true;	
											updatePreview();
											
										}
						        	}
						       		
						   		});
							}else{
								alert("Please select an image to upload");
								return;
							}
						     
    					}else{
    						insertImageIntoEditor($("#Form_ImagePickerForm_ExistingImage").attr('data-link'), title, editorType);
    						$( this ).dialog( "close" );
							$('#Form_EditForm_Content').focus();
							updateFlag = true;	
							updatePreview();
    					}
					},
					"Cancel": function() {
						$( this ).dialog( "close" );
						$('#Form_EditForm_Content').focus();
					}	
				}
			});

		}
		
		function insertImageIntoEditor(link, title, editorType){
			if(editorType == 'markdown'){
				title = title ? '"' + title + '"' : '';
				$.markItUp({replaceWith:'![' + title + '](' + link + ' ' + title + ')'});
			}else if(editorType == 'wiki'){
				$.markItUp({replaceWith:'[[Image:' + link + '|' + title + ']]'});
			}
		}
		
		
		// autocomplete functionality for image field
			
		$( "#Form_ImagePickerForm_ExistingImage" ).livequery(function(){
			$(this).autocomplete({
				source: function( request, response ) {
					$.get(controllerurl + '/linklist', {term : request.term, type : 'image'}, function(data){
						if(data && data.length){
							var items = [];
							for (var id = 0; id < data.length; id++){
								items.push({
									label : data[id].Label,
									value : data[id].Title,
									id : data[id].ID,
									link : data[id].Link
								});
							}
							response(items);
						}
					});
				},
				minLength: 2,
				select: function( event, ui ) {
					$(this).val(ui.item.label);
					$(this).attr('data-id', ui.item.id);
					$(this).attr('data-link', ui.item.link);
				}
			});
			
		});
		
		
		// update dialog image input field on image type radio button change
		
		$('#Form_ImagePickerForm #Type input:radio').live('change', function(){
			type = $(this).val();
			if(type == 'new'){
				$('#NewImage').show().next().hide();
			}else if(type == 'existing'){
				$('#NewImage').hide().next().show();
			}	
		});
		
		$('#NewImage').live('change', function(){
			
		});
		
		
		// link dialog window
		
		window.simpleWikiLinkDialog = function(editorType){
	
			$("#dialogContent").load(controllerurl + '/linkpicker').dialog({
		        title: "Insert a link",
				modal: true,
				autoOpen: true,
				height: 500,
				width: 500,
				open: function(event, ui){
					// hide title field for wiki editor as it is not supported
					if(editorType == 'wiki'){
						$('#dialogContent #Title').livequery(function(){
							$(this).hide();	
						});
					}
				},
				buttons: {
					"Insert Link": function() {
						link ='';
						if($('#Type input:radio:checked').val() == 'external'){
							link = $('#Form_LinkPickerForm_Link').val();
						}else{
							link = $('#Form_LinkPickerForm_Link').attr('data-link');
						}
						
						// validate link selection
						if(!link){
							alert('Please select or insert a link');
							$('#Form_LinkPickerForm_Link').focus();
							return false;
						}
						
						if(editorType == 'markdown'){
							if($('#Form_LinkPickerForm_Title').val()){
								title = '"' + $('#Form_LinkPickerForm_Title').val() + '"';	
							}else{
								title = '';
							}
							
							$.markItUp({
								openWith:'[', 
								closeWith:'](' + link + ' ' + title + ')',
								placeHolder:'Your text to link here...'
							});
						}else if(editorType == 'wiki'){
							$.markItUp({
								openWith:'[' + link + ' ', 
								closeWith:']',
								placeHolder:'Your text to link here...' 
							});
						}
						
						updateFlag = true;	
						updatePreview();
						
						$( this ).dialog( "close" );
						$('#Form_EditForm_Content').focus();
					},
					"Cancel": function() {
						$( this ).dialog( "close" );
						$('#Form_EditForm_Content').focus();
					}	
				}
			});
		}
		
		
		// autocomplete functionality for link field
			
		$( "#Form_LinkPickerForm_Link" ).livequery(function(){
			if($('#Type input:radio:checked').val() != 'external'){
				$(this).autocomplete({
					source: function( request, response ) {
						$.get(controllerurl + '/linklist', {term : request.term, type : $('#Type input:radio:checked').val()}, function(data){
							if(data && data.length){
								var items = [];
								for (var id = 0; id < data.length; id++){
									items.push({
										label : data[id].Label,
										value : data[id].Title,
										id : data[id].ID,
										link : data[id].Link
									});
								}
								response(items);
							}
						});
					},
					minLength: 2,
					select: function( event, ui ) {
						$(this).val(ui.item.label);
						$(this).attr('data-id', ui.item.id);
						$(this).attr('data-link', ui.item.link);
					}
				});
			}
		});
		
		
		// update dialog link input field on link type radio button change
		
		$('#Form_LinkPickerForm #Type input:radio').live('change', function(){
			type = $(this).val();
			label = $('#Link label');
			input = $('#Link input');
			if(type == 'file'){
				label.text('Search by file name');
				input.val('');
			}else if(type == 'external'){
				label.text('Enter external link URL');
				input.val('http://');
			}else if(type == 'page'){
				label.text('Search by page title');
				input.val('');
			}
			input.focus();		
		});
		
		
		// warn about formatting issues before changing editor type
		
		origin = $('#Form_EditForm_EditorType option:selected').val();
		$('#Form_EditForm_EditorType').change(function(){
			if($('#Form_EditForm_Content').val().length >10){
				choice = confirm('Changing the editor type with existing content can upset formatting. Change anyway?');
				if(choice == true){
					$('#Form_EditForm').submit();
				}else{
					$(this).val(origin);
				}
			}else{
				$('#Form_EditForm').submit();
			}
		});	


	});
})(jQuery);




		
		