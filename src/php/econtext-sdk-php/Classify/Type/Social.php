<?php

namespace eContext\Classify\Type;
use eContext\Classify\Classify;

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