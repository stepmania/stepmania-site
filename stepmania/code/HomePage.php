<?php
class HomePage extends SiteTree {
	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->addFieldToTab('Root.Link', new TreeDropdownField("NewsForumID", "Forum to pull content from", "SiteTree"));

		return $fields;
	}
	public static $has_one = array(
		"NewsForum" => "Forum"
	);
}
class HomePage_Controller extends Page_Controller {
	public static $allowed_actions = array ();
	public function init() {
		parent::init();
	}
}
