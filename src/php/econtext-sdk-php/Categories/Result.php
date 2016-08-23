<?php

namespace eContext\Categories;
use eContext\Categories\Density;
use eContext\Client;
use eContext;

/**
 * Categories Results
 *
 * @author jspalink
 */
class Result extends eContext\Result {
    
    protected function loadPage($data) {
        if($data === null) {
            return null;
        }
        parent::loadPage($data);
        $this->results = $this->get(Density::JSON_INNER_ELEMENT, $data[Client::JSON_OUTER_ELEMENT], array());
        return True;
    }
    
}