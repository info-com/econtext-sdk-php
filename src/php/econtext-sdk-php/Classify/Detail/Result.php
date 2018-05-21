<?php
/**
 * Created by PhpStorm.
 * User: jspalink
 * Date: 5/21/18
 * Time: 12:16 PM
 */

namespace eContext\Classify\Detail;
use eContext\Client;
use eContext\Classify\Classify;


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
class Result  extends \eContext\Result {
    
    protected $inner;
    protected $categories;
    protected $vocabularies;
    protected $flags;
    
    protected function loadPage($data) {
        if($data === null) {
            return null;
        }
        $default = array();
        if($this->callSizes != null && $this->hasError()) {
            $default = array_pad(array(), $this->callSizes[($this->currentPage-1)], ["error_code"=> $this->getErrorCode(), "error_message"=>$this->getErrorMessage()]);
        }
        $this->inner = $this->get(Classify::JSON_INNER_ELEMENT, $data[Client::JSON_OUTER_ELEMENT], array());
        $this->categories = $this->get('categories', $this->inner, array());
        $this->vocabularies = $this->get('vocabularies', $this->inner, array());
        $this->flags = $this->get('flags', $this->inner, array());
        $this->_results = $this->get('results', $this->inner, $default);
        $this->_results = $this->get('results', $this->inner, $default);
        return true;
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
    
    /**
     * The yieldResults function will, at some point, implement objects (matches, ngrams, etc)
     * so I'm providing this function to access the raw data for the time being...  That way, we
     * can use this but not break anything moving forward
     */
    public function yield_results() {
        foreach($this->yieldPages() as $result) {
            foreach($this->_results as $x) {
                yield $x;
            }
        }
    }
    
    public function getInner() {
        return $this->inner;
    }
    
    public function getCategory($categoryId) {
        return $this->get($categoryId, $this->categories);
    }
    
    public function getCategories() {
        return $this->categories;
    }
    
    public function getVocabulary($vocabularyId) {
        return $this->get($vocabularyId, $this->vocabularies);
    }
    
    public function getVocabularies() {
        return $this->vocabularies;
    }
    
    /**
     * Retrieve a specific overlay result.  Overlays are identified using a
     * category id key, and an overlay key.  For example, you might be
     * interested in the overlay for category 1 (Abba Hair Care) in overlay
     * iab2016 (IAB Overlay for 2016).
     *
     * @param int $categoryId  Category ID to lookup
     * @param string $overlayId Overlay ID to lookup
     * @return mixed
     */
    public function getOverlay($categoryId, $overlayId) {
        if(($overlay = $this->get($categoryId, $this->overlay)) !== null) {
            return $this->get($overlayId, $overlay);
        }
        return null;
    }
    
    public function getOverlays() {
        return $this->overlay;
    }
}