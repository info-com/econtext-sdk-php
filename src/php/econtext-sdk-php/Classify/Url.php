<?php

namespace eContext\Classify;

class Url extends Classify {
    
    const CLASSIFY_TYPE = "url";
    const URL_REQUEST_CLASS = "/url";
    const ARRAY_LIMIT = 1;
    
    protected function newResultSet() {
        return new Results\Html($this->client->getTempDir());
    }
}
