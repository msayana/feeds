<?php
require_once('AbstractScraperBuilder.php');
require_once('WebScraper.php');
/**
 * Created by PhpStorm.
 * User: maddy.sayana
 * Date: 19/03/15
 * Time: 18:53
 */

class WebScraperBuilder extends AbstractScraperBuilder{

    private $_webscraper;

    public function __construct(){
        $this->_webscraper = new WebScraper();
    }


    public function getHtml($url)
    {
        return $this->_webscraper->scrape($url);
    }

    public function setExcludedWords($excludedWords){
        $this->_webscraper->setExcludedWords($excludedWords);
    }

    public function buildSharedNews($html){
        $this->_webscraper->buildSharedNews($html);
    }


    public function getSharedJSON()
    {
        return $this->_webscraper->getSharedJSON();
    }
}