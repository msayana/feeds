<?php
require_once('../lib/WebScraperBuilder.php');
require_once('../lib/WebScraperDirector.php');

try{

    $webScaperBuilder = new WebScraperBuilder();
    $webScraperDirector = new WebScraperDirector($webScaperBuilder);

    $webScraperDirector->buildSharedNews('http://www.bbc.co.uk/news');
    $jsonResult = $webScraperDirector->getSharedNews();

    echo $jsonResult;

} catch(Exception $e) {
    echo $e->getMessage();
}