<?php

namespace eContext\Classify\Type;
use eContext\Classify\Classify;

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