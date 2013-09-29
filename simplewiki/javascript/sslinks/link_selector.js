
SimpleWiki = {};

(function ($) {
	/**
	 * Create a new link selector
	 * 
	 * @param form
	 * 			The form that this selector is attached to
	 * 
	 * @param type
	 * 			The type of link being selected (image or href)
	 * 
	 * @param preview
	 * 			The ID of the preview div in the page
	 * 			
	 */
	SimpleWiki.LinkSelector = function (form, type, preview) {
		this.form = form;
		// type of link we're inserting, either an image or page link
		this.type = type;

		this.preview = preview;
		
		// details about an item that has been selected by the user, if any
		this.itemDetails = null;

		this.init();
	}

	SimpleWiki.LinkSelector.prototype = {
		init: function () {
			if (this.type == 'image') {
				$('#TargetPage').hide();
			} else {
				$('#TargetFile').hide();
			}
			
			
			var $this = this; 
			// watch for the tree value changing
			var fieldType = $this.type == 'image' ? 'TargetFile' : 'TargetPage';
			var field = $('#' + $this.form + '_' + fieldType);
			
			// when it changes, we want to have an event handler to load
			// an appropriate preview of the selection
			var treeInput = $('#TreeDropdownField_'+$this.form+'_'+fieldType)[0];

			treeInput.observeMethod('Change', function () {
				// load the preview image
				var url = 'ssimplewiki/objectdetails';
				var id = field.val();

				$.getJSON(url, {ID: id, type: $this.type}, function (data) {
					if (!data.error) {
						var details = {
							'src' : data.Filename,
							'alt' : data.Name,
							'width' : data.width,
							'height' : data.height,
							'title' : data.Title
						};

						$this.itemDetails = details;
						if ($this.type == 'image') {
							$('#ContentPreview').html('<img src="'+data.Filename+'" />');
						} else {
							$('#ContentPreview').html('<iframe src="'+data.Link+'"></iframe>');
						}
					} else {
						alert(data.message);
					}
				});
			});

			$('#' + this.form).submit(function () {
				// get the selected item
				var id = field.val();
				
				// add the link
				if ($this.type == 'href') {
					var url = "[sitetree_link id=" + id + ']';
					$this.ssInsertLink(tinyMCEPopup.editor, url);
					window.close();
				} else if ($this.type == 'image') {
					if ($this.itemDetails) {
						$this.ssInsertImage(tinyMCEPopup.editor, $this.itemDetails);
						window.close();
					} else {
						alert("Invalid image");
					}
				}
				return false;
			});
		},

		ssInsertLink: function(ed, attributes) {
		    var v = attributes;
			var s = ed.selection, e = ed.dom.getParent(s.getNode(), 'A');

			if (tinymce.is(attributes, 'string'))
				attributes = {href : attributes};

			function set(e) {
				tinymce.each(attributes, function(v, k) {
				    if(k == 'innerHTML') e.innerHTML = v;
					else ed.dom.setAttrib(e, k, v);
				});
			};

		    if(attributes.innerHTML && !ed.selection.getContent()) {
	            if(tinymce.isIE) var rng = ed.selection.getRng();
		        e = ed.getDoc().createElement('a');
		        e.innerHTML = attributes.innerHTML;
		        e.href = attributes.href;
		        s.setNode(e);
		        if(tinymce.isIE) tinyMCE.activeEditor.selection.setRng(rng);
	        }
			if (!e) {
				ed.execCommand('CreateLink', false, 'javascript:mctmp(0);');
				tinymce.each(ed.dom.select('a'), function(e) {
					if (e.href == 'javascript:mctmp(0);') set(e);
				});
			} else {
				if (attributes.href)
					set(e);
				else
					ed.dom.remove(e, 1);
			}
		},
		
		ssInsertImage: function (ed, attributes, captionText) {
			el = ed.selection.getNode();
			var html;

			if(captionText) {
				html = '<div style="width: ' + attributes.width + 'px;" class="captionImage ' + attributes['class'] + '">';
				html += '<img id="__mce_tmp" />';
				html += '<p class="caption">' + captionText + '</p>';
				html += '</div>';
			} else {
				html = '<img id="__mce_tmp" />';
			}

			if(el && el.nodeName == 'IMG') {
				ed.dom.setAttribs(el, attributes);
			} else {
				ed.execCommand('mceInsertContent', false, html, {
					skip_undo : 1
				});
				
				ed.dom.setAttribs('__mce_tmp', attributes);
				ed.dom.setAttrib('__mce_tmp', 'id', '');
				ed.undoManager.add();
			}
		}
	}
})(jQuery);