<?php
require_once "jbbcode/Parser.php";

class SMBBCodeParser extends TextParser {
	private static $autolink_urls = true;
	private static $allow_smilies = true;
	private static $smilies_location = null;
	
	public static function smilies_location() {
		Deprecation::notice('3.2', 'Use the "SMBBCodeParser.smilies_location" config setting instead');
		if(!SMBBCodeParser::$smilies_location) {
			return 'stepmania/images/smilies';
		}
		return static::config()->smilies_location;
	}

	public static function set_icon_folder($path) {
		Deprecation::notice('3.2', 'Use the "SMBBCodeParser.smilies_location" config setting instead');
		static::config()->smilies_location = $path;
	} 
	

	public static function autolinkUrls() {
		Deprecation::notice('3.2', 'Use the "SMBBCodeParser.autolink_urls" config setting instead');
		return static::config()->autolink_urls;
	}
	

	public static function disable_autolink_urls($autolink = false) {
		Deprecation::notice('3.2', 'Use the "SMBBCodeParser.autolink_urls" config setting instead');
		static::config()->autolink_urls = $autolink;
	}
	

	public static function smiliesAllowed() {
		Deprecation::notice('3.2', 'Use the "SMBBCodeParser.allow_smilies" config setting instead');
		return static::config()->allow_smilies;
	}
	

	public static function enable_smilies() {
		Deprecation::notice('3.2', 'Use the "SMBBCodeParser.allow_smilies" config setting instead');
		static::config()->allow_smilies = true;
	}
	
	
	public static function usable_tags() {
		return new ArrayList(
			array(
				new ArrayData(array(
					"Title" => _t('SMBBCodeParser.BOLD', 'Bold Text'),
					"Example" => '[b]<b>'._t('SMBBCodeParser.BOLDEXAMPLE', 'Bold').'</b>[/b]'
				)),
				new ArrayData(array(
					"Title" => _t('SMBBCodeParser.ITALIC', 'Italic Text'),
					"Example" => '[i]<i>'._t('SMBBCodeParser.ITALICEXAMPLE', 'Italics').'</i>[/i]'
				)),
				new ArrayData(array(
					"Title" => _t('SMBBCodeParser.UNDERLINE', 'Underlined Text'),
					"Example" => '[u]<u>'._t('SMBBCodeParser.UNDERLINEEXAMPLE', 'Underlined').'</u>[/u]'
				)),
				new ArrayData(array(
					"Title" => _t('SMBBCodeParser.STRUCK', 'Struck-out Text'),
					"Example" => '[s]<s>'._t('SMBBCodeParser.STRUCKEXAMPLE', 'Struck-out').'</s>[/s]'
				)),
				new ArrayData(array(
					"Title" => _t('SMBBCodeParser.COLORED', 'Colored text'),
					"Example" => '[color=blue]'._t('SMBBCodeParser.COLOREDEXAMPLE', 'blue text').'[/color]'
				)),
				new ArrayData(array(
					"Title" => _t('SMBBCodeParser.ALIGNEMENT', 'Alignment'),
					"Example" => '[align=right]'._t('SMBBCodeParser.ALIGNEMENTEXAMPLE', 'right aligned').'[/align]'
				)),
				new ArrayData(array(
					"Title" => _t('SMBBCodeParser.CODE', 'Code Block'),
					"Description" => _t('SMBBCodeParser.CODEDESCRIPTION', 'Unformatted code block'),
					"Example" => '[code]'._t('SMBBCodeParser.CODEEXAMPLE', 'Code block').'[/code]'
				)),
				new ArrayData(array(
					"Title" => _t('SMBBCodeParser.STEPS', 'Steps Block'),
					"Description" => _t('SMBBCodeParser.STEPSDESCRIPTION', 'Steps block'),
					"Example" => '[steps]:l: :d: :u: :r:[/steps]'
				)),
				new ArrayData(array(
					"Title" => _t('SMBBCodeParser.EMAILLINK', 'Email link'),
					"Description" => _t('SMBBCodeParser.EMAILLINKDESCRIPTION', 'Create link to an email address'),
					"Example" => "[email]you@yoursite.com[/email]"
				)),
				new ArrayData(array(
					"Title" => _t('SMBBCodeParser.EMAILLINK', 'Email link'),
					"Description" => _t('SMBBCodeParser.EMAILLINKDESCRIPTION', 'Create link to an email address'),
					"Example" => "[email=you@yoursite.com]Email[/email]"
				)),
				new ArrayData(array(
					"Title" => _t('SMBBCodeParser.UNORDERED', 'Unordered list'),
					"Description" => _t('SMBBCodeParser.UNORDEREDDESCRIPTION', 'Unordered list'),
					"Example" => '[ulist][*]'._t('SMBBCodeParser.UNORDEREDEXAMPLE1', 'unordered item 1').'[/ulist]'
				)),			
				new ArrayData(array(
					"Title" => _t('SMBBCodeParser.IMAGE', 'Image'),
					"Description" => _t('SMBBCodeParser.IMAGEDESCRIPTION', 'Show an image in your post'),
					"Example" => "[img]http://www.website.com/image.jpg[/img]"
				)),
				new ArrayData(array(
					"Title" => _t('SMBBCodeParser.LINK', 'Website link'),
					"Description" => _t('SMBBCodeParser.LINKDESCRIPTION', 'Link to another website or URL'),
					"Example" => '[url]http://www.website.com/[/url]'
				)),
				new ArrayData(array(
					"Title" => _t('SMBBCodeParser.LINK', 'Website link'),
					"Description" => _t('SMBBCodeParser.LINKDESCRIPTION', 'Link to another website or URL'),
					"Example" => "[url=http://www.website.com/]Website[/url]"
				))
			)
		);
	}
	
	public function useable_tagsHTML(){
		$useabletags = "<ul class='bbcodeExamples'>";
		foreach($this->usable_tags()->toArray() as $tag){
			$useabletags = $useabletags."<li><span>".$tag->Example."</span></li>";
		}
		return $useabletags."</ul>";
	}
	
	/**
	 * Main BBCode parser method. This takes plain jane content and
	 * runs it through so many filters 
	 *
	 * @return Text
	 */
	public function parse() {
//		$this->content = str_replace(array('&', '<', '>'), array('&amp;', '&lt;', '&gt;'), $this->content);

		$parser = new JBBCode\Parser();
		$parser->addCodeDefinitionSet(new SMBBCodeDefinitionSet());

		$parser->parse($this->content);

		$this->content = $parser->getAsHtml();

		// make sure newlines aren't all wonky
		$this->content = str_replace("\n", "<br />", $this->content);

		// add smilies				
		if($this->config()->allow_smilies) {
			$smilies = array(
				// arrows
				'#(?<!\w):b:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/blank.png'> ",
				'#(?<!\w):l:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/l4.png'> ",
				'#(?<!\w):d:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/d4.png'> ",
				'#(?<!\w):u:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/u4.png'> ",
				'#(?<!\w):r:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/r4.png'> ",

				'#(?<!\w):l4:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/l4.png'> ",
				'#(?<!\w):d4:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/d4.png'> ",
				'#(?<!\w):u4:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/u4.png'> ",
				'#(?<!\w):r4:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/r4.png'> ",

				'#(?<!\w):l8:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/l8.png'> ",
				'#(?<!\w):d8:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/d8.png'> ",
				'#(?<!\w):u8:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/u8.png'> ",
				'#(?<!\w):r8:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/r8.png'> ",

				'#(?<!\w):l12:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/l12.png'> ",
				'#(?<!\w):d12:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/d12.png'> ",
				'#(?<!\w):u12:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/u12.png'> ",
				'#(?<!\w):r12:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/r12.png'> ",

				'#(?<!\w):l16:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/l16.png'> ",
				'#(?<!\w):d16:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/d16.png'> ",
				'#(?<!\w):u16:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/u16.png'> ",
				'#(?<!\w):r16:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/r16.png'> ",

				'#(?<!\w):l32:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/l32.png'> ",
				'#(?<!\w):d32:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/d32.png'> ",
				'#(?<!\w):u32:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/u32.png'> ",
				'#(?<!\w):r32:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/r32.png'> ",

				// holds
				'#(?<!\w):hb:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/hb.png'> ",
				'#(?<!\w):he:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/he.png'> ",

				// rolls
				'#(?<!\w):rb:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/rb.png'> ",
				'#(?<!\w):re:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/re.png'> ",

				// mine
				'#(?<!\w):m:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/re.png'> ",

				// machine buttons
				'#(?<!\w):ml:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/ml.png'> ",
				'#(?<!\w):ms:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/ms.png'> ",
				'#(?<!\w):mr:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/mr.png'> ",

				// pump buttons
				'#(?<!\w):pc:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/pc.png'> ",
				'#(?<!\w):ul:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/ul.png'> ",
				'#(?<!\w):ur:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/ur.png'> ",
				'#(?<!\w):dl:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/dl.png'> ",
				'#(?<!\w):dr:(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/arrows/dr.png'> ",


				/*
				'#(?<!\w):D(?!\w)#i'         => " <img src='".SMBBCodeParser::smilies_location(). "/grin.gif'> ", // :D
				'#(?<!\w):\)(?!\w)#i'        => " <img src='".SMBBCodeParser::smilies_location(). "/smile.gif'> ", // :)
				'#(?<!\w):-\)(?!\w)#i'        => " <img src='".SMBBCodeParser::smilies_location(). "/smile.gif'> ", // :-)
				'#(?<!\w):\((?!\w)#i'        => " <img src='".SMBBCodeParser::smilies_location(). "/sad.gif'> ", // :(
				'#(?<!\w):-\((?!\w)#i'        => " <img src='".SMBBCodeParser::smilies_location(). "/sad.gif'> ", // :-(
				'#(?<!\w):p(?!\w)#i'         => " <img src='".SMBBCodeParser::smilies_location(). "/tongue.gif'> ", // :p
				'#(?<!\w)8-\)(?!\w)#i'     => " <img src='".SMBBCodeParser::smilies_location(). "/cool.gif'> ", // 8-)
				'#(?<!\w):\^\)(?!\w)#i' => " <img src='".SMBBCodeParser::smilies_location(). "/confused.gif'> " // :^)
				*/
			);
			$this->content = preg_replace(array_keys($smilies), array_values($smilies), $this->content);
		}
		return $this->content;
	}
}
