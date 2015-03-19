<?php
require_once('../lib/WebScraper.php');
require_once('../lib/WebScraperException.php');
require_once('../lib/WebScraperBuilder.php');
require_once('../lib/WebScraperDirector.php');
/**
 * Created by PhpStorm.
 * User: maddy.sayana
 * Date: 19/03/15
 * Time: 19:03
 */

class WebScraperTest extends PHPUnit_Framework_TestCase{

    public function testFunctioningSite(){


        $webScaperBuilder = new WebScraperBuilder();
        $webScraperDirector = new WebScraperDirector($webScaperBuilder);

        $html = $webScraperDirector->getHtml('http://www.bbc.co.uk/news/');

        $this->assertTrue($webScraperDirector->isHtml($html));

    }

    /**
     * @expectedException     WebScraperException
     */
    public function testNonFunctioningSite(){

        $webScaperBuilder = new WebScraperBuilder();
        $webScraperDirector = new WebScraperDirector($webScaperBuilder);
        $webScraperDirector->buildSharedNews('http://asdsdsad/news/');

    }

    public function testFileSize(){

        $webScraper = new WebScraper();
        $fileSize = $webScraper->getFileSize('http://www.bbc.co.uk/news');
        $this->assertNotEquals(0, $fileSize);

    }


    public function testExcludeWords(){

        $webScraper = new WebScraper();

        $wordsList = array(
            'hello' => 2,
            'battle'=> 1,
            'is' => 5,
            'how' => 2,
            'on' => 4,
            'computer'=>'1',
            'are' => 3,
            'the' => 5
        );

        $webScraper->setExcludedWords('is,on,are,the');
        $newWordsList = $webScraper->excludeWords($wordsList);

        $this->assertEquals(4, sizeof($newWordsList));
    }

    public function testMaxWordUsed(){

        $webScraper = new WebScraper();

        $wordsList = array(
            'hello' => 3,
            'battle'=> 1,
            'is' => 5,
            'how' => 2,
            'on' => 4,
            'computer'=>'1',
            'are' => 3,
            'the' => 5
        );

        $webScraper->setExcludedWords('is,on,are,the');
        $newWordsList = $webScraper->excludeWords($wordsList);

        $key = $webScraper->maxUsedWord($newWordsList);
        $this->assertEquals('hello', $key);

    }

    /**
     * @expectedException     WebScraperException
     * @expectedExceptionMessage No HTML or URL provided to scrape the site
     */
    public function testInvalidXPathElements(){

        $webScraper = new WebScraper();
        $webScraper->getXpathElements(null, null);
    }


    /**
     * @expectedException     WebScraperException
     * @expectedExceptionMessage No Xpath elements found in HTML
     */

    public function testInvalidSharedNews(){

        $webScraper = new WebScraper();
        $webScraper->buildSharedNews('<html><body><table></table></body></html>');
    }

} 