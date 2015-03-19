<?php
/**
 * Created by PhpStorm.
 * User: maddy.sayana
 * Date: 19/03/15
 * Time: 17:49
 */

abstract class AbstractScraperBuilder {

    abstract function buildSharedNews($html);
    abstract function getHtml($url);
    abstract function getSharedJSON();

} 