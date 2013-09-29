<?php
/* WikiRetriever
 * Version 1.0
 * Copyright 2005, Steve Blinch
 * http://code.blitzaffe.com
 *
 * This class fetches one or more documents from Wikipedia (or any other MediaWiki wiki),
 * translates them into HTML, and returns them.
 *
 *
 * EXAMPLE
 *
 * //
 * // Simple WikiRetriever usage example
 * //
 * require_once('class_WikiRetriever.php');
 *
 * $url = "http://www.yourwikisite.com";
 * $titles = array("First_article","Second_article");
 * $wiki = &new WikiRetriever();
 * if (!$wiki->retrieve($url,$titles)) {
 * 		die("Error: $wiki->error");
 * } else {
 * 		var_dump($wiki->pages);
 * }
 *
 *
 * LICENSE
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 * 
 *  
 *
 */
// require_once("libcurlemu/class_HTTPRetriever.php");
require_once("class_XMLParser.php");

//require_once(dirname(__FILE__)."/class_WikiWrapper.php");
require_once(dirname(__FILE__)."/class_WikiParser.php");

class WikiRetriever {
	
	function WikiRetriever() {
		$this->http = &new HTTPRetriever();
		$this->http->caching = 3600;
		$this->xml = &new XMLParser();
	}
	
	function retrieve($wikiurl,$titles,$level=0) {
		
		$this->_recursionlevel = $level;
		
		$this->wikiurl = $wikiurl;
		
		//if (substr($wikiurl,-1)!='/') $wikiurl .= '/';
		if (!is_array($titles)) $titles = array($titles);
		$titles = implode("\r\n",$titles);

		$values = array(
			'action'=>'submit',
			'pages'=>$titles,
			'curonly'=>'true',
		);

		if ($this->http->post($wikiurl."Special:Export",$this->http->make_query_string($values))) {
			$this->parse_xml($this->http->response);

			return true;
		} else {
			$this->error = "HTTP request error: #{$this->http->result_code}: {$this->http->result_text}";
			return false;
		}
	}
	

	function retrieve_urls($wikiurl,$urls) {
		$urls = explode("\n",$urls);
		foreach ($urls as $k=>$url) {
			$url = trim($url);
			$p = strrpos($url,'/');
			$urls[$k] = substr($url,$p+1);
		}
		return $this->retrieve($wikiurl,$urls);
	}
	
	function parse_xml($xml) {
		$this->xmldata = $this->xml->xmlize($xml);
		
		$this->pages = array();
		
		foreach ($this->xmldata['mediawiki']['#']['page'] as $k=>$page) {
			$page = $page['#'];
			$pagedata = array(
				'title'=>$page['title'][0]['#'],
				'timestamp'=>$page['revision'][0]['#']['timestamp'][0]['#'],
				'text'=>$page['revision'][0]['#']['text'][0]['#'],
			);
			$pagedata['html'] = $this->wiki_html($pagedata['text'],$pagedata['title']);
			$this->pages[] = $pagedata;	
		}
		
		return true;
	}
	
	function wiki_html($text,$title) {
		$parser = &new WikiParser();
		$output = $parser->parse($text,$title);
		
		// handle redirection
		if ($parser->redirect) {
			$this->_recursionlevel++;
			if ($this->recursionlevel==10) {
				$output = "Redirection limit exceeded";
			} else {
				$sub = &new WikiRetriever();
				$sub->retrieve($this->wikiurl,$parser->redirect,$this->_recursionlevel);
				$output = $sub->pages[0]['html'];
				unset($sub);
			}
		}
		
		return $output;
		
		/*
		//$text = preg_replace('/\[\[([^\|\]]*?\|)?([^\]]+?)\]\]/i','\\2',$text);
		$parser = &new Parser();
		$options = &new WikiWrapperOptions();
		$title = &new Title();
		$content = $parser->parse($text,$title,$options);
		return $content->mText;
		*/
	}
}
?>