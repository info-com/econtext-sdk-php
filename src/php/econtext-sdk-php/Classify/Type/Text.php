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

class Text extends Classify {
    
    const CLASSIFY_TYPE = "text";
    const URL_REQUEST_CLASS = "/text";
    const ARRAY_LIMIT = 1;
    
    protected function newResultSet() {
        return new \eContext\Classify\Results\Text();
    }
}

function main($username, $password, $texts) {
    $client = new \eContext\Client($username, $password);
    $classify = new Text($client);
    $classify->setData($texts);
    $classify->setParameter("flags", true);
    $classify->setParameter("entities", true);
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
    $a = new CLI("Run a single URL.");
    $a->addArg("i", "input", "input", "input", true, null, "store", "string", "A string to classify as text");
    $a->addArg("u", "username", "username", "username", true, null, "store", "string", "eContext API Username");
    $a->addArg("p", "password", "password", "password", true, null, "store", "string", "eContext API Password");
    $args = $a->parse();
    $texts = array($a->getArg('input')->getValue());
    $username = $a->getArg('username')->getValue();
    $password = $a->getArg('password')->getValue();
    main($username, $password, $texts);
}
