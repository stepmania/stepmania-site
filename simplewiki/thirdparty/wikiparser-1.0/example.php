<?php

//
// Simple WikiRetriever usage example
//
require_once('class_WikiRetriever.php');

$url = "http://www.yourwikisite.com";
$titles = array("First_article","Second_article");
$wiki = &new WikiRetriever();
if (!$wiki->retrieve($url,$titles)) {
die("Error: $wiki->error");
} else {
var_dump($wiki->pages);
}


?>
