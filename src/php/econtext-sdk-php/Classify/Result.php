<?php

namespace eContext\Classify;
use eContext\Client;


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
    
    protected $categories;
    protected $overlay;
    protected $translate;
    
    protected function loadPage($data) {
        parent::loadPage($data);
        $this->inner = $this->get(Classify::JSON_INNER_ELEMENT, $data[Client::JSON_OUTER_ELEMENT], array());
        $this->categories = $this->get('categories', $this->inner, array());
        $this->overlay = $this->get('overlay', $this->inner, array());
        $this->translate = $this->get('translate', $this->inner, array());
    }
    
    public function getCategory($cid) {
        return $this->get($cid, $this->categories);
    }
    
    public function getCategories() {
        return $this->categories;
    }
    
    /**
     * Retrieve a specific overlay result.  Overlays are identified using a 
     * category id key, and an overlay key.  For example, you might be 
     * interested in the overlay for category 1 (Abba Hair Care) in overlay
     * iab2016 (IAB Overlay for 2016).
     * 
     * @param int $cid  Category ID to lookup
     * @param string $overlayId Overlay ID to lookup
     * @return mixed
     */
    public function getOverlay($cid, $overlayId) {
        if(($overlay = $this->get($cid, $this->overlay)) !== null) {
            return $this->get($overlayId, $overlay);
        }
        return null;
    }
    
    public function getOverlays() {
        return $this->overlay;
    }
    
    public function getTranslate() {
        return $this->translate;
    }
}