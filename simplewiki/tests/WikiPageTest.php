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

class WikiPageTest extends SapphireTest
{
	public static $fixture_file = 'simplewiki/tests/WikiPageTest.yml';
	
	function testParseNewPages()
	{
		$wp = new DummyWikiFormatter();
		
		$content = 'page content [[with a mix]] of good urls';
		$newPages = $wp->parseNewPagesFrom($content);
		$this->assertEquals('with a mix', $newPages[0]);
		
		// check to make sure that other malformed entries fail
		$content = 'page with [[a malformed url?]] here, and [[ something [ ]] else';
		$newPages = $wp->parseNewPagesFrom($content);
		$this->assertEquals(0, count($newPages)); 
	}
	
	function testCreateNewPage()
	{
		$page = $this->objFromFixture('WikiPage', 'wiki2');
		
		$page->Content = 'page content [[with a mix]] of good urls';
		// save and make sure there's a page with the title 'with a mix'
		$page->write();

		$children = $page->Children();
		
		$this->assertEquals(1, count($children));
		$this->assertEquals('with a mix', $children->First()->Title);
	}

	function testGetWikiRoot()
	{
		// get the root page of the wiki
		$page = $this->objFromFixture('WikiPage', 'wiki4');
		$root = $page->getWikiRoot();

		$this->assertEquals('Wiki', $root->Title);
	}

	function testLockTimeout()
	{
		$page = $this->objFromFixture('WikiPage', 'wiki2');

		$page->Content = 'page content [[with a mix]] of good urls';
		// save and make sure there's a page with the title 'with a mix'
		$page->write();

		// now lock the page, we expect the 'lockExpiry' to be in the future
		$now = time();
		$page->lock();
		$expiry = strtotime($page->WikiLockExpiry);

		// the lock should be at least minDiff in the future
		$minDiff = WikiPage::$lock_time - 5;

		$this->assertTrue($expiry - $now > $minDiff);

	}
}

class DummyWikiFormatter extends SimpleWikiFormatter {
	public function getFormatterName(){
		return "Dummy";
	}

	/**
	 * Get the CMS field for editing this kind of element
	 * @param DataObject $wikiPage
	 * 			The page being edited
	 */
	public function getEditingField(DataObject $wikiPage){
	
	}

	/**
	 * Format the content for output
	 *
	 * @param DataObject $wikiPage
	 * 			The page being edited
	 */
	public function formatRaw($string){
	
	}

	/**
	 * Get a URL that links to a page showing relevant help functionality
	 */
	public function getHelpUrl() {
	
	}
}