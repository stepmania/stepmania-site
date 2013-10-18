<?php
class GamePage extends Page {
	static $db = array();
	static $has_one = array();
}

class GamePage_Controller extends Page_Controller {
	static $allowed_actions = array();
	function init() {
		parent::init();
		Requirements::javascript("stepmania/javascript/jquery-2.0.3.min.js");
		Requirements::javascript("stepmania/javascript/sm.js");
		//Requirements::themedCSS('stepmania','stepmania','all');
	}
}
