<?php

namespace eContext\Classify\Type;
use eContext\Classify\Classify;

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