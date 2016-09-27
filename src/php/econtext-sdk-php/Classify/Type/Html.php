<?php

namespace eContext\Classify\Type;
use eContext\Classify\Classify;
use Zenya\CLI;

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
