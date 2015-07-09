<?php

class AnalyticsSiteConfig extends DataExtension {
	private static $db = array(
		'AnalyticsID' => "Text",
		'AnalyticsDomain' => "Text"
	);

	public function updateCMSFields(FieldList $fields) {
		$fields->addFieldToTab("Root.Main", new TextField("AnalyticsID", "Analytics ID"));
		$fields->addFieldToTab("Root.Main", new TextField("AnalyticsDomain", "Analytics Domain"));
	}

/*
	public function getAnalyticsDomain() {
		if ($this->AnalyticsDomain) {
			// A valid GA domain shouldn't include a scheme. Trust nobody.
			$url = parse_url($this->AnalyticsDomain);
			$valid_scheme = isset($url["scheme"]) ? false : true;

			if ($valid_scheme)
				return $this->AnalyticsDomain;
		}

		$url = parse_url(Director::absoluteBaseURL());
		return $url["host"];
	}
*/
}
