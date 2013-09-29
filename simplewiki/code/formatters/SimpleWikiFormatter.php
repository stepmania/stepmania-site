<?php
/**
 * Interface defining a formatter that can be used in wikis
 *
 * @author Marcus Nyeholt <marcus@silverstripe.com.au>
 * @license BSD License (http://silverstripe.org/BSD-License)
 */
abstract class SimpleWikiFormatter {

	/**
	 * Analyse content just after it's saved. This is useful for creating
	 * new pages etc where referenced by particular formatting
	 */
	public function analyseSavedContent($wikiPage) {
		$newPages = $this->parseNewPagesFrom($wikiPage->Content);

		foreach ($newPages as $pageTitle) {
			$trimmedTitle = trim($pageTitle);

			// need to trim the title before doing the search
			$page = DataObject::get_one('WikiPage', '"SiteTree"."Title" = \'' . Convert::raw2sql($trimmedTitle) . '\'');

			if (!$page) {
				// it's a new page, so create that
				$page = new WikiPage();
				$page->Title = $trimmedTitle;
				$page->MenuTitle = $trimmedTitle;
				$page->ParentID = $wikiPage->ID;
				$page->write();

				// publish if we're on autopublish
				if (WikiPage::$auto_publish) {
					$page->doPublish();
				}
			}

			$replacement = '<a href="[sitetree_link id=' . $page->ID . ']">' . $pageTitle . '</a>';
			$wikiPage->Content = str_replace('[[' . $pageTitle . ']]', $replacement, $wikiPage->Content);
		}
	}
	
	
	/**
	 * Separated into a separate method for testing
	 * 
	 * @param String $content
	 * @return array
	 */
	public function parseNewPagesFrom($content) {
		$pages = array();
		if (preg_match_all('/\[\[([\w\s_.-]+)\]\]/', $content, $matches)) {
			// exit(print_r($matches));
			foreach ($matches[1] as $pageTitle) {
				$pages[] = $pageTitle;
			}
		}

		return $pages;
	}
	
	/**
	 * Allows a formatter to change the fields available in the backend for a wiki page. 
	 *
	 * @param FieldSet $fields 
	 */
	public function updateCMSFields($fields) {
		// default behaviour is to change to a textarea - the HTML formatter type will 
		// just override this behaviour
		$fields->replaceField('Content', new TextareaField('Content', _t('WikiPage.CONTENT', 'Content'), 15));
	}

	/**
	 * Gets the type of this formatter as a string
	 */
	public abstract function getFormatterName();

	/**
	 * Get the CMS field for editing this kind of element
	 * @param DataObject $wikiPage
	 * 			The page being edited
	 */
	public abstract function getEditingField(DataObject $wikiPage);

	/**
	 * Format the content for output
	 *
	 * @param DataObject $wikiPage
	 * 			The page being edited
	 */
	public function formatContent(DataObject $wikiPage){
		return $this->formatRaw($wikiPage->Content);
	}
	
	public abstract function formatRaw($string);

	/**
	 * Get a URL that links to a page showing relevant help functionality
	 */
	public abstract function getHelpUrl();
}