<?php
class HomePage extends SiteTree {
	function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->addFieldToTab('Root.Link', new TreeDropdownField("NewsForumID", "Forum to pull content from", "SiteTree"));

		return $fields;
	}
	static $has_one = array(
		"NewsForum" => "Forum"
	);
}
class HomePage_Controller extends Page_Controller {
	static $allowed_actions = array ();
	function init() {
		parent::init();
	}
}
