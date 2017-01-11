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
