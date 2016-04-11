# SiteConfig

## Introduction

SiteConfig provides a `Settings` tab in the admin section allowing users to set
site wide global configuration.

## Requirements

 * SilverStripe 3.2

## Installation

Installation can be done either by composer or by manually downloading the
release from Github.

### Via composer

`composer require "silverstripe/siteconfig:*"`

### Manually

 1.  Download the module from [the releases page](https://github.com/silverstripe/silverstripe-siteconfig/releases).
 2.  Extract the file (if you are on windows try 7-zip for extracting tar.gz files
 3.  Make sure the folder after being extracted is named 'siteconfig' 
 4.  Place this directory in your sites root directory. This is the one with framework and cms in it.

### Configuration

After installation, make sure you rebuild your database through `dev/build`.

## Development and Contribution ##

If you would like to make changes to the SilverStripe core codebase, we have an extensive [guide to contributing code](http://doc.silverstripe.org/framework/en/misc/contributing/code).

## Links ##

 * [Requirements](http://doc.silverstripe.org/framework/en/installation/server-requirements)
 * [Changelogs](http://doc.silverstripe.org/framework/en/changelogs/)
 * [Bugtracker: Framework](https://github.com/silverstripe/silverstripe-framework/issues)
 * [Bugtracker: CMS](https://github.com/silverstripe/silverstripe-cms/issues)
 * [Bugtracker: Installer](https://github.com/silverstripe/silverstripe-installer/issues)
 * [Forums](http://silverstripe.org/forums)
 * [Developer Mailinglist](https://groups.google.com/forum/#!forum/silverstripe-dev)

## License ##

	Copyright (c) 2006-2015, SilverStripe Limited - www.silverstripe.com
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
