<?php

namespace eContext\Categories;
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

    /**
     * Iterate through a list of social classification results.  Each result
     * will include keyword flags, if requested, NLP entities, if requested, and
     * scored_categories and scored_keywords.
     */
    public function yieldResults() {
        foreach($this->yieldPages() as $result) {
            yield $this->results;
        }
    }
    
}