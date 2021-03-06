<?php

namespace eContext\Classify;

class Text extends Classify {
    
    const CLASSIFY_TYPE = "text";
    const URL_REQUEST_CLASS = "/text";
    const ARRAY_LIMIT = 1;
    
    protected function newResultSet() {
        return new Results\Text($this->client->getTempDir());
    }
}
