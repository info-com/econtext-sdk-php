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
class Keywords extends Result {
    
    protected $mappings;

    protected function loadPage($data) {
        if($data === null) {
            return null;
        }
        parent::loadPage($data);
        $defaultMappings = array();
        $defaultResults = array();
        if($this->callSizes != null && $this->hasError()) {
            $defaultResults = array_pad(array(), $this->callSizes[($this->currentPage-1)], ["error_code"=> $this->getErrorCode(), "error_message"=>$this->getErrorMessage()]);
            $defaultMappings = array_pad(array(), $this->callSizes[($this->currentPage-1)], ["error_code"=> $this->getErrorCode(), "error_message"=>$this->getErrorMessage()]);
            $size = count($defaultMappings);
            $error = var_export($this->error, true);
            error_log("An error occurred: {$error}");
            error_log("defaultMappings set to contain {$size} elements");
        }
        $this->results = $this->get('results', $this->inner, $defaultResults);
        $this->mappings = $this->get('mappings', $this->inner, $defaultMappings);
        return True;
    }
    
    /**
     * Iterate through a list of keyword mapping results.
     * 
     */
    public function yieldMappings() {
        while(($result = $this->loadPage($this->getPage())) !== null) {
            foreach($this->mappings as $mapping) {
                yield $mapping;
            }
        }
        return;
    }
    
    /**
     * Iterate through a list of keyword classification results.  These results
     * will include flags, if provided, and category_id, if provided
     */
    public function yieldResults() {
        foreach($this->yieldPages() as $result) {
            foreach($this->results as $x) {
                yield $x;
            }
        }
    }
}