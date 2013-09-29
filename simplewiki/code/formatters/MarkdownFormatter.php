<?php

/**
 * A Wiki formatter for Markdown syntax
 *
 * @author Marcus Nyeholt <marcus@silverstripe.com.au>
 * @license BSD License (http://silverstripe.org/BSD-License)
 */
class MarkdownFormatter extends SimpleWikiFormatter {

	public function getFormatterName() {
		return "Markdown";
	}

	/**
	 * Note that we explicity pass the dataobject content here - if we don't, then
	 * the form calls the Content() method of the CONTROLLER that the form is associated with, which means
	 * that it returns the parsed content!
	 *
	 * @param DataObject $wikiPage
	 * @return MarkItUpField
	 */
	public function getEditingField(DataObject $wikiPage) {
		return new MarkItUpField('Content', '', 'markdown', 30, 20);
	}

	public function formatRaw($string) {
		include_once SIMPLEWIKI_DIR . '/thirdparty/php-markdown-extra-1.2.4/markdown.php';
		return Markdown($string);
	}

	public function getHelpUrl() {
		return 'http://michelf.com/projects/php-markdown/extra/';
	}

}
