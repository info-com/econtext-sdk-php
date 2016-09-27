<?php

namespace eContext\Classify\Type;
use eContext\Classify\Classify;

class Url extends Classify {
    
    const CLASSIFY_TYPE = "url";
    const URL_REQUEST_CLASS = "/url";
    const ARRAY_LIMIT = 1;
    
    protected function newResultSet() {
        return new \eContext\Classify\Results\Html();
    }
}

function main($username, $password, $urls) {
    $client = new \eContext\Client($username, $password);
    $classify = new Url($client);
    $classify->setData($urls);
    $classify->setParameter("flags", true);
    $result = $classify->classify(1); // returns a classify result
    $i = 0;
    foreach($result->yieldResults() as $mapping) {
        echo $urls[$i++].PHP_EOL;
        print_r($mapping);
        exit;
        foreach($mapping['scored_categories'] as $cat_info) {
            $cid = $cat_info['category_id'];
            echo " * {$result->getCategory($cid)['name']}".PHP_EOL;
        }
    }
}