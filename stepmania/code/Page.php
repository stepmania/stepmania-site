<?php
class Page extends SiteTree {
	function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->addFieldToTab('Root.Special', new HTMLEditorField("BannerContent", "Banner Content"));

		return $fields;
	}
	private static $db = array(
		"BannerContent" => "HTMLText"
	);
}

class Page_Controller extends ContentController {
	private static $allowed_actions = array();

	public function init() {
		parent::init();

		Requirements::clear();
		Requirements::block("cms/css/SilverStripeNavigator.css");
		//Requirements::block(THIRDPARTY_DIR . "/jquery/jquery.js");
	}

}