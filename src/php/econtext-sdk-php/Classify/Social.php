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
