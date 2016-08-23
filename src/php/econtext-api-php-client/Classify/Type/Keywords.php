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

class Keywords extends Classify {
    
    const CLASSIFY_TYPE = "keywords";
    const URL_REQUEST_CLASS = "/keywords";
    const ARRAY_LIMIT = 500;
    
    protected function newResultSet() {
        return new \eContext\Classify\Results\Keywords();
    }
}

function main($username, $password, $keywords) {
    $client = new \eContext\Client($username, $password);
    $classify = new Keywords($client);
    $classify->setData($keywords);
    $classify->setParameter("flags", true);
    $result = $classify->classify(1); // returns a classify result
    foreach($result->yieldResults() as $mapping) {
        $cid = $mapping['category_id'];
        $mapping = json_encode($mapping);
        echo "{$result->getCurrentPage()} .. {$mapping} .. {$result->getCategory($cid)['name']}".PHP_EOL;
    }
}

if (basename($argv[0]) == basename(__FILE__)) {
    $a = new CLI("Run a list of keywords through.");
    $a->addArg("i", "input", "input", "input", true, null, "store", "string", "Comma separated list of keywords");
    $a->addArg("u", "username", "username", "username", true, null, "store", "string", "eContext API Username");
    $a->addArg("p", "password", "password", "password", true, null, "store", "string", "eContext API Password");
    $args = $a->parse();
    $keywords = explode(",", $a->getArg('input')->getValue());
    $username = $a->getArg('username')->getValue();
    $password = $a->getArg('password')->getValue();
    main($username, $password, $keywords);
}
