<?php

namespace eContext\Classify;

class Html extends Classify {
    
    const CLASSIFY_TYPE = "html";
    const URL_REQUEST_CLASS = "/html";
    const ARRAY_LIMIT = 1;
    
    protected function newResultSet() {
        return new Results\Html($this->client->getTempDir());
    }
}
