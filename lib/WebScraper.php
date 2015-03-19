<?php

require_once('WebScraperException.php');
/**
 * Created by PhpStorm.
 * User: maddy.sayana
 * Date: 19/03/15
 * Time: 18:11
 */

/**
 * Class WebScraper
 */
class WebScraper {


    /**
     * @var Comma Seperated String of excluded Words;
     */
    private $_excludedWords;
    /**
     * @var Array of shared news Items
     */
    private $_sharedJSON;

    /**
     * @param String $excludedWords
     */
    public function setExcludedWords($excludedWords)
    {
        $this->_excludedWords = $excludedWords;
    }


    /**
     * @return String
     */
    public function getExcludedWords()
    {
        return $this->_excludedWords;
    }



    /**
     * Return HTML for the given URL
     * @param $url
     * @return mixed
     * @throws WebScraperException
     */
    public function scrape($url){

        $ch = curl_init($url);

        // set options for the call
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36');

        $html = curl_exec($ch);

        if(curl_exec($ch) === false || curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200){
            throw new WebScraperException('The site with url '.$url.' could NOT be scraped. Please check the URL and retry');
        }

        // Close handle
        curl_close($ch);

        return $html;

    }


    /**
     * Builds
     * @param $html
     * @throws WebScraperException
     */
    public function buildSharedNews($html){

        //Create empty array
        $results = array('results' => array());

        // Fetch Xpath elements
        $elements = $this->getXpathElements(null, "//div[@id='most-popular']/div[@class='panel open']/ol/li", $html);


        if (!is_null($elements) && $elements->length != 0) {

            foreach ($elements as $element) {

                $nodes = $element->childNodes;

                foreach ($nodes as $node) {
                    $item = array();

                    if($node->nodeName == 'a'){

                        $itemUrl = $node->getAttribute("href");
                        $fileSize = $this->getFileSize($itemUrl);

                        $item['href'] = $itemUrl;
                        $item['size'] = $fileSize;
                        $item['most_used_word'] = $this->getMostUsedWord($itemUrl);
                        $item['title'] = trim(preg_replace('/\s+/', ' ', $node->nodeValue));//$node->nodeValue;

                        array_push($results['results'], $item);
                    }

                }

            }

        } else {
            throw new WebScraperException('No Xpath elements found in HTML');
        }

        $this->_sharedJSON = $results;

    }

    /**
     * Returns JSON
     * @return string
     */
    public function getSharedJSON(){
        return json_encode($this->_sharedJSON, JSON_PRETTY_PRINT);
    }


    /**
     * Returns the most reoccured word in the provided URL
     * @param $url
     * @return mixed
     * @throws WebScraperException
     */
    public function getMostUsedWord($url){

        $fullStory = '';

        $elements = $this->getXpathElements($url, "//div[@class='story-body']/p");

        if (!is_null($elements)) {
            foreach ($elements as $element) {

                $nodes = $element->childNodes;
                foreach ($nodes as $node) {
                    $storyPara = $node->nodeValue;
                    $fullStory .= $storyPara;
                }
            }
        }

        $wordCount = array_count_values(str_word_count($fullStory, 1));

        $wordsAfterExcluding = $this->excludeWords($wordCount);

        return $this->maxUsedWord($wordsAfterExcluding);


    }


    /**
     * Returns Xpath elements for given URL or html page
     * @param $url
     * @param $expression
     * @param null $html
     * @return DOMNodeList
     * @throws WebScraperException
     */
    public function getXpathElements($url, $expression, $html = null){


        if(!isset($url) && !isset($html)){
            throw new WebScraperException('No HTML or URL provided to scrape the site');
        }

        if(!isset($html)){
            $html = $this->scrape($url);
        }

        // create DOM object
        $doc = new DOMDocument();

        $doc->preserveWhiteSpace = false;

        // load our html
        @$doc->loadHTML($html);

        // create new xpath object
        $xpath = new DOMXpath($doc);

        $elements = $xpath->query($expression);

        return $elements;

    }

    /**
     * Returns the key of the max value in the array
     * @param $words
     * @return mixed
     */
    public function maxUsedWord($words){

        $value = max($words);
        return array_search($value, $words);

    }

    /**
     * Excludes words from a given words list array
     * @param $words
     * @return mixed
     */

    public function excludeWords($words){

        $excludedWords = explode(',', $this->getExcludedWords());

        foreach($excludedWords as $key => $word){
            if(array_key_exists($word, $words)){
                unset($words[$word]);
            }
        }

        return $words;
    }


    /**
     * Returns file size of the given url in byte/kb/mb/gb
     * @param $url
     * @return int|string
     */
    public function getFileSize($url){
        # Get all header information
        $data = get_headers($url, true);
        # Look up validity
        if (isset($data['Content-Length'])){
            # Return file size
            $size =  (int) $data['Content-Length'];

            # size smaller then 1kb
            if ($size < 1024) return $size . ' Byte';
            # size smaller then 1mb
            if ($size < 1048576) return sprintf("%4.2f KB", $size/1024);
            # size smaller then 1gb
            if ($size < 1073741824) return sprintf("%4.2f MB", $size/1048576);
            # size smaller then 1tb
            if ($size < 1099511627776) return sprintf("%4.2f GB", $size/1073741824);
            # size larger then 1tb
            else return sprintf("%4.2f TB", $size/1073741824);
        }

        return 0;
    }



} 