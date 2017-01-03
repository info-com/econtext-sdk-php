<?php

namespace eContext\Classify\Type;
use eContext\Classify\Classify;

class Url extends Classify {
    
    const CLASSIFY_TYPE = "url";
    const URL_REQUEST_CLASS = "/url";
    const ARRAY_LIMIT = 1;
    
    protected function newResultSet() {
        return new \eContext\Classify\Results\Html();
    }
}
