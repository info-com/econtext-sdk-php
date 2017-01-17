<?php

namespace eContext\Keywords\Search;
use eContext\Client;
use eContext\Result;

class Results extends Result {

    protected $categories;

    protected function loadPage($data) {
        if($data === null) {
            return null;
        }
        parent::loadPage($data);
        $response = $this->get(Search::JSON_INNER_ELEMENT, $data[Client::JSON_OUTER_ELEMENT], array());
        $this->categories = $this->get('categories', $response, array());
        $this->results = $this->get(Search::JSON_INNER_ELEMENT, $response, array());
        return True;
    }

    public function getCategory($categoryId) {
        return $this->get($categoryId, $this->categories);
    }

    public function getCategories() {
        return $this->categories;
    }

    /**
     * Iterate through a list of social classification results.  Each result
     * will include keyword flags, if requested, NLP entities, if requested, and
     * scored_categories and scored_keywords.
     */
    public function yieldResults() {
        foreach($this->yieldPages() as $result) {
            foreach($this->results as $x) {
                yield $x;
            }
        }
    }

}