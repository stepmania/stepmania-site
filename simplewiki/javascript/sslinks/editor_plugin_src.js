(function() {
	// Load plugin specific language pack
	// tinymce.PluginManager.requireLangPack('sslinks');

	tinymce.create('tinymce.plugins.SSLinks', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');
			ed.addCommand('mceSimpleLink', function() {
				var se = ed.selection;

				// No selection and not in link
				if (se.isCollapsed() && !ed.dom.getParent(se.getNode(), 'A')) {
					return;
				}
				ed.windowManager.open({
					url : 'ssimplewiki/linkselector?type=href',
					width : 700,
					height : 600,
					inline : 1
				}, {
					plugin_url : url, // Plugin absolute URL
					insert_type: 'link'
				});
			});
			
			ed.addCommand('mceSimpleImage', function() {
				ed.windowManager.open({
					url : 'ssimplewiki/linkselector?type=image',
					width : 700,
					height : 600,
					inline : 1
				}, {
					plugin_url : url, // Plugin absolute URL
					insert_type: 'image'
				});
			});

			// Register example button
			ed.addButton('ss_simplelink', {
				title : 'sslinks.desc',
				cmd : 'mceSimpleLink',
				'class': 'mce_link'
			});
			
			ed.addButton('ss_simpleimage', {
				title : 'sslinks.desc',
				cmd : 'mceSimpleImage',
				'class': 'mce_image'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('simplelink', n.nodeName == 'IMG');
			});
		},

		/**
		 * Creates control instances based in the incomming name. This method is normally not
		 * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
		 * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
		 * method can be used to create those.
		 *
		 * @param {String} n Name of the control to create.
		 * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
		 * @return {tinymce.ui.Control} New control instance or null if no control was created.
		 */
		createControl : function(n, cm) {
			return null;
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
				longname : 'Simple frontend links',
				author : 'Marcus Nyeholt',
				authorurl : 'http://silverstripe.com.au',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/example',
				version : "1.0"
			};
		}
	});

	// replace the relevant bits of the base tag in tinymce... bit of a 
	// nuisance, but necessary for images to appear. Somehow the backend
	// page does this nicely on its own?
	tinymce.documentBaseURL = document.getElementsByTagName('base')[0].href;
		
	// Register plugin
	tinymce.PluginManager.add('sslinks', tinymce.plugins.SSLinks);
})();
