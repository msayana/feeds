<?php
require_once('AbstractScraperBuilder.php');
/**
 * Created by PhpStorm.
 * User: maddy.sayana
 * Date: 19/03/15
 * Time: 17:50
 */

abstract class AbstractScraperDirector {

    abstract function __construct(AbstractScraperBuilder $builder);

} 