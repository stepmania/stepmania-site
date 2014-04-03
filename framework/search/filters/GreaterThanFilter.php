<?php
/**
 * Selects numerical/date content greater than the input
 *
 * Can be used by SearchContext and DataList->filter, eg;
 * Model::get()->filter("Field1:GreaterThan", $value);
 *
 * @package framework
 * @subpackage search
 */
class GreaterThanFilter extends ComparisonFilter {

	protected function getOperator() {
		return ">";
	}

	protected function getInverseOperator() {
		return "<=";
	}

}
