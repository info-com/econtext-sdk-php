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

class Social extends Classify {
    
    const CLASSIFY_TYPE = "social";
    const URL_REQUEST_CLASS = "/social";
    const ARRAY_LIMIT = 500;
    
    protected function newResultSet() {
        return new \eContext\Classify\Results\Social();
    }
}

function main($username, $password, $posts) {
    $client = new \eContext\Client($username, $password);
    $classify = new Social($client);
    $classify->setData($posts);
    $classify->setParameter("flags", true);
    $result = $classify->classify(5); // returns a classify result
    $i = 0;
    foreach($result->yieldResults() as $mapping) {
        echo "{$posts[$i++]}".PHP_EOL;
        foreach($mapping['scored_categories'] as $cat_info) {
            $cid = $cat_info['category_id'];
            echo " * {$result->getCategory($cid)['name']}".PHP_EOL;
        }
    }
}

if (basename($argv[0]) == basename(__FILE__)) {
    $a = new CLI("Run a single social media post.");
    $a->addArg("i", "input", "input", "input", true, null, "store", "string", "A social media post");
    $a->addArg("u", "username", "username", "username", true, null, "store", "string", "eContext API Username");
    $a->addArg("p", "password", "password", "password", true, null, "store", "string", "eContext API Password");
    $args = $a->parse();
    $posts = array($a->getArg('input')->getValue());
    $username = $a->getArg('username')->getValue();
    $password = $a->getArg('password')->getValue();
    main($username, $password, $posts);
}
