<?php

namespace eContext\Classify\Results;
use eContext\Classify\Result;

/**
 * A common interface for classification results.  Will always contain a
 * "categories" dictionary, may contain an "overlay" dictionary, and then a list
 * of results associated with each input.
 * 
 * The result set will use temporary files to store results - this will allow
 * us to send through a large list of keywords, and not maintain them and all
 * the associated data in memory.  Each time you switch to a new temp file set
 * of results, they will be pulled into memory, and categories, overlays, and
 * results will be overwritten.
 */
class Social extends Result {

    protected function loadPage($data) {
        if($data === null) {
            return null;
        }
        parent::loadPage($data);
        $default = array();
        if($this->callSizes != null && $this->hasError()) {
            $default = array_pad(array(), $this->callSize[($this->currentPage-1)], ["error_code"=> $this->getErrorCode(), "error_message"=>$this->getErrorMessage()]);
        }
        $this->results = $this->get('results', $this->inner, $default);
        return True;
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