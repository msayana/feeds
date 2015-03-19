<?php
require_once('AbstractScraperDirector.php');

/**
 * Created by PhpStorm.
 * User: maddy.sayana
 * Date: 19/03/15
 * Time: 19:04
 */

/**
 * Class WebScraperDirector - A director class which directs the builder class to build the scraper
 */
class WebScraperDirector extends AbstractScraperDirector{

    CONST EXCLUDE_WORDS = 'the,a,is,and,or,I,to,in,of,on';

    /**
     * @var AbstractScraperBuilder
     */
    private $_webScraperBuilder;


    public function __construct(AbstractScraperBuilder $builder)
    {
        $this->_webScraperBuilder = $builder;
    }

    /**
     * Returns HTML of the provided URL - used for PHP Unit tests
     * @param $url
     * @return mixed
     */
    public function getHtml($url)
    {
        return $this->_webScraperBuilder->getHtml($url);
    }

    /**
     * Scrapes Webpage for the given URL.  Builds array of most popular shared news items
     * @param $url
     */
    public function buildSharedNews($url)
    {
        $html = $this->getHtml($url);
        $this->_webScraperBuilder->setExcludedWords(self::EXCLUDE_WORDS);
        $this->_webScraperBuilder->buildSharedNews($html);
    }

    /**
     * Returns JSON format of shared news
     * @return mixed
     */
    public function getSharedNews()
    {
        return $this->_webScraperBuilder->getSharedJSON();
    }


    /**
     * Checks if the string is a html page
     * @param $htmlContent
     * @return bool
     */
    public function isHtml($htmlContent){
        if (preg_match('/<html.*>/', $htmlContent)) {
            return true;
        }
        return false;
    }

}