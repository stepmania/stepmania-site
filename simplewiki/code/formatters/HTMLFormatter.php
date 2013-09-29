<?php
/**
 * HTML simplewiki formatter
 *
 * @author Marcus Nyeholt <marcus@silverstripe.com.au>
 * @license BSD License (http://silverstripe.org/BSD-License)
 */
class HTMLFormatter extends SimpleWikiFormatter {

	public function getFormatterName() {
		return "HTML";
	}
	
	public function updateCMSFields($fields) {
		// we don't want to change the field type for HTML
	}

	public function getEditingField(DataObject $wikiPage) {
		return new HtmlEditorField('Content', '', 30, 20);
	}

	public function formatRaw($string) {
		return $string;
	}

	public function getHelpUrl() {
		return 'http://tinymce.moxiecode.com/';
	}

}
