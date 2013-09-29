<?php
/**

Copyright (c) 2009, SilverStripe Australia PTY LTD - www.silverstripe.com.au
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the 
      documentation and/or other materials provided with the distribution.
    * Neither the name of SilverStripe nor the names of its contributors may be used to endorse or promote products derived from this software 
      without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE 
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE 
LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE 
GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, 
STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY 
OF SUCH DAMAGE.
 
*/

/**
 * A text field that wraps around the markitup JS plugin from
 * 
 * http://markitup.jaysalvat.com/
 * @author Marcus Nyeholt <marcus@silverstripe.com.au>
 *
 */
class MarkItUpField extends TextareaField
{
	protected $markupType = 'wiki';
	
	/**
	 * Includes the JavaScript neccesary for this field to work using the {@link Requirements} system.
	 */
	public static function include_js($type) {
		Requirements::javascript('jsparty/jquery/jquery.js');
		Requirements::javascript('jsparty/jquery/jquery_improvements.js');
		Requirements::javascript('sapphire/thirdparty/jquery-livequery/jquery.livequery.js');
		Requirements::javascript('sapphire/thirdparty/jquery-ui/jquery.ui.core.js');
		Requirements::javascript('sapphire/thirdparty/jquery-ui/jquery.ui.position.js');
		Requirements::javascript('sapphire/thirdparty/jquery-ui/jquery.ui.widget.js');
		Requirements::javascript('sapphire/thirdparty/jquery-ui/jquery.ui.mouse.js');
		Requirements::javascript('sapphire/thirdparty/jquery-ui/jquery.ui.draggable.js');
		Requirements::javascript('sapphire/thirdparty/jquery-ui/jquery.ui.dialog.js');
		Requirements::javascript('sapphire/thirdparty/jquery-ui/jquery.ui.autocomplete.js');
		Requirements::javascript('sapphire/thirdparty/jquery-form/jquery.form.js');
		
		Requirements::javascript('simplewiki/javascript/markitup/jquery.markitup.js');
		
		Requirements::css('sapphire/thirdparty/jquery-ui-themes/base/jquery.ui.all.css');
		Requirements::css('sapphire/thirdparty/jquery-ui-themes/base/jquery.ui.theme.css');
		Requirements::css('sapphire/thirdparty/jquery-ui-themes/base/jquery.ui.dialog.css');
		Requirements::css('sapphire/thirdparty/jquery-ui-themes/base/jquery.ui.autocomplete.css');
		Requirements::css('simplewiki/javascript/markitup/skins/markitup/style.css', 'all');

		switch ($type) {
			case 'wiki': {
				Requirements::javascript('simplewiki/javascript/markitup/sets/wiki/set.js');
				Requirements::css('simplewiki/javascript/markitup/sets/wiki/style.css');
				break;
			}
			case 'markdown': {
				Requirements::javascript('simplewiki/javascript/markitup/sets/markdown/set.js');
				Requirements::css('simplewiki/javascript/markitup/sets/markdown/style.css');
				break;
			}
			default: {
			}
		}
	}
	
	
	/**
	 * @see TextareaField::__construct()
	 */
	public function __construct($name, $title = null, $type = 'wiki', $rows = 30, $cols = 20, $value = '', $form = null) {
		
		parent::__construct($name, $title, $rows, $cols, $value, $form);
		$this->markupType = $type;
		self::include_js($type);
	}

	/**
	 * @return string
	 */
	function Field() {
		$settings = ucfirst($this->markupType);
		// add JS
		Requirements::customScript('jQuery().ready(function () { jQuery("#'.$this->id().'").markItUp(my'.$settings.'Settings)});');

		
		$attributes = array (
				'class'   => $this->extraClass(),
				'rows'    => $this->rows,
				'style'   => 'width: 90%; height: ' . ($this->rows * 16) . 'px', // prevents horizontal scrollbars
				'id'      => $this->id(),
				'name'    => $this->name
			);

		if ($this->readonly) {
			$attributes['readonly'] = 'readonly';
		}

		$val = str_replace('&amp;#13;', '', htmlentities($this->value, ENT_COMPAT, 'UTF-8'));
		return $this->createTag (
			'textarea',
			$attributes,
			$val
		);
	}
}


?>