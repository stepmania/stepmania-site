<?php
require_once 'jbbcode/CodeDefinition.php';
require_once 'jbbcode/CodeDefinitionBuilder.php';
require_once 'jbbcode/CodeDefinitionSet.php';
require_once 'jbbcode/validators/CssColorValidator.php';
require_once 'jbbcode/InputValidator.php';

class UrlValidator implements JBBCode\InputValidator
{
	public function validate($input)
	{
		if (preg_match("[javascript:]", $input))
			return false;
		$valid = !!filter_var($input, FILTER_VALIDATE_URL);
		if (!$valid)
			$valid = !!filter_var(Director::absoluteBaseURL() . $input, FILTER_VALIDATE_URL);
		return $valid;
	}
}

class NickValidator implements JBBCode\InputValidator
{
	public function validate($input)
	{
		return (bool) preg_match('/^[A-z0-9\-#., ()%]+$/', $input) && strlen($input) < 80;
	}
}

class YoutubeEmbed extends JBBCode\CodeDefinition {
	public function __construct()
	{
		parent::__construct();
		$this->setTagName("youtube");
	}

	public function asHtml(JBBCode\ElementNode $el)
	{
		$content = "";
		foreach($el->getChildren() as $child)
			$content .= $child->getAsBBCode();
			
		$foundMatch = preg_match('/(\?v=|\?.+&v=|\/\d\/|\/embed\/|\/v\/|\.be\/)([a-zA-Z0-9\-\_]+)/', $content, $matches);
		if(!$foundMatch)
			return $el->getAsBBCode();
		else
			return "<iframe width=\"640\" height=\"390\" src=\"http://www.youtube.com/embed/".$matches[2]."\" frameborder=\"0\" allowfullscreen></iframe>";
	}
}

class SMBBCodeDefinitionSet implements JBBCode\CodeDefinitionSet
{
	protected $definitions = array();

	public function __construct()
	{
		$urlValidator = new UrlValidator();

		/* [b] bold tag */
		$builder = new JBBCode\CodeDefinitionBuilder('b', '<strong>{param}</strong>');
		array_push($this->definitions, $builder->build());

		/* [i] italics tag */
		$builder = new JBBCode\CodeDefinitionBuilder('i', '<em>{param}</em>');
		array_push($this->definitions, $builder->build());

		/* [u] underline tag */
		$builder = new JBBCode\CodeDefinitionBuilder('u', '<u>{param}</u>');
		array_push($this->definitions, $builder->build());

		/* [url] link tag */
		$builder = new JBBCode\CodeDefinitionBuilder('url', '<a href="{param}" rel="nofollow">{param}</a>');
		$builder->setParseContent(false)->setBodyValidator($urlValidator);
		array_push($this->definitions, $builder->build());

		/* [url=http://example.com] link tag */
		$builder = new JBBCode\CodeDefinitionBuilder('url', '<a href="{option}" rel="nofollow">{param}</a>');
		$builder->setUseOption(true)->setParseContent(true)->setOptionValidator($urlValidator);
		array_push($this->definitions, $builder->build());

		/* [img] image tag */
		$builder = new JBBCode\CodeDefinitionBuilder('img', '<img src="{param}" alt="" />');
		$builder->setUseOption(false)->setParseContent(false)->setBodyValidator($urlValidator);
		array_push($this->definitions, $builder->build());

		/* [img=alt text] image tag */
		$builder = new JBBCode\CodeDefinitionBuilder('img', '<img src="{param}" alt="{option}" />');
		$builder->setUseOption(true)->setOptionValidator($urlValidator);
		array_push($this->definitions, $builder->build());

		/* [color] color tag */
		$builder = new JBBCode\CodeDefinitionBuilder('color', '<span style="color: {option}">{param}</span>');
		$builder->setUseOption(true)->setOptionValidator(new \JBBCode\validators\CssColorValidator());
		array_push($this->definitions, $builder->build());

		// TODO: cite for users/post ids
		//$builder = new CodeDefinitionBuilder('url', '<a href="{option}">{param}</a>');
		/* [quote] */
		$builder = new JBBCode\CodeDefinitionBuilder('quote', '<q>{param}</q>');
		array_push($this->definitions, $builder->build());

		/* [quote=user] */
		$builder = new JBBCode\CodeDefinitionBuilder('quote', '<q cite="{option}">{param}</q>');
		$builder->setUseOption(true)->setOptionValidator(new NickValidator());
		array_push($this->definitions, $builder->build());

		/* [small=#color] */
		$builder = new JBBCode\CodeDefinitionBuilder('small', '<span class="small" style="color: {option};">{param}</span>');
		$builder->setUseOption(true)->setOptionValidator(new \JBBCode\validators\CssColorValidator());
		array_push($this->definitions, $builder->build());

		/* [small] */
		$builder = new JBBCode\CodeDefinitionBuilder('small', '<span class="small">{param}</span>');
		array_push($this->definitions, $builder->build());

		/* [large=#color] */
		$builder = new JBBCode\CodeDefinitionBuilder('large', '<span class="large" style="color: {option};">{param}</span>');
		$builder->setUseOption(true)->setOptionValidator(new \JBBCode\validators\CssColorValidator());
		array_push($this->definitions, $builder->build());

		/* [large] */
		$builder = new JBBCode\CodeDefinitionBuilder('large', '<span class="large">{param}</span>');
		array_push($this->definitions, $builder->build());

		/* [s] */
		$builder = new JBBCode\CodeDefinitionBuilder('s', '<s>{param}</s>');
		array_push($this->definitions, $builder->build());

		/* [center] */
		$builder = new JBBCode\CodeDefinitionBuilder('center', '<span class="center">{param}</span>');
		array_push($this->definitions, $builder->build());

		/* [rainbow] */
		$builder = new JBBCode\CodeDefinitionBuilder('rainbow', '<span class="rainbow">{param}</span>');
		array_push($this->definitions, $builder->build());

		/* [steps] */
		$builder = new JBBCode\CodeDefinitionBuilder('steps', '<div class="stepsblock">{param}</div>');
		array_push($this->definitions, $builder->build());

		/* [*] */
//		$builder = new JBBCode\CodeDefinitionBuilder('*', '<li>{param}</li>');
//		array_push($this->definitions, $builder->build());

		/* [code] */
		$builder = new JBBCode\CodeDefinitionBuilder('code', '<pre class="code">{param}</pre>');
		$builder->setParseContent(false);
		array_push($this->definitions, $builder->build());

        /* [abbr=description] abbreviation */
        $builder = new JBBCode\CodeDefinitionBuilder('abbr', '<abbr title="{option}">{param}</abbr>');
        $builder->setUseOption(true);
        array_push($this->definitions, $builder->build());
	}

	public function getCodeDefinitions()
	{
		return $this->definitions;
	}

}
