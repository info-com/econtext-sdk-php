<?php

namespace eContext\Classify\Type;
use eContext\Classify\Classify;
use eContext\Classify\Result;
use Zenya\CLI;


if (basename($argv[0]) == basename(__FILE__)) {
    require_once('/Users/jspalink/dev/econtext-api-php-client/vendor/autoload.php');
    #require_once('../../Client.php');
    #require_once('../Classify.php');
    #require_once('../Result.php');
    require_once('/Users/jspalink/dev/zenya-php-lib//src/Zenya/CLI.php');
    require_once('/Users/jspalink/dev/zenya-php-lib/src/Zenya/CLI/Argument.php');
}

class Html extends Classify {
    
    const CLASSIFY_TYPE = "html";
    const URL_REQUEST_CLASS = "/html";
    const ARRAY_LIMIT = 1;
    
    protected function newResultSet() {
        return new \eContext\Classify\Results\Html();
    }
}

function main($username, $password, $htmls) {
    $client = new \eContext\Client($username, $password);
    $classify = new Html($client);
    $classify->setData($htmls);
    $classify->setParameter("flags", true);
    $result = $classify->classify(1); // returns a classify result
    foreach($result->yieldResults() as $mapping) {
        print_r($mapping);
        foreach($mapping['scored_categories'] as $cat_info) {
            $cid = $cat_info['category_id'];
            echo " * {$result->getCategory($cid)['name']}".PHP_EOL;
        }
    }
}

if (basename($argv[0]) == basename(__FILE__)) {
    $a = new CLI("Run a single HTML page.");
    $a->addArg("i", "input", "input", "input", true, null, "store", "string", "An HTML page");
    $a->addArg("u", "username", "username", "username", true, null, "store", "string", "eContext API Username");
    $a->addArg("p", "password", "password", "password", true, null, "store", "string", "eContext API Password");
    $args = $a->parse();
    $htmls = array($a->getArg('input')->getValue());
    $username = $a->getArg('username')->getValue();
    $password = $a->getArg('password')->getValue();
    main($username, $password, $htmls);
}
