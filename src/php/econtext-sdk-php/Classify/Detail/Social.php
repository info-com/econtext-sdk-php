<?php
/**
 * Created by PhpStorm.
 * User: jspalink
 * Date: 5/21/18
 * Time: 11:31 AM
 */

namespace eContext\Classify\Detail;
use eContext\Classify\Classify;

class Social extends Classify {
    const CLASSIFY_TYPE = "social";
    const URL_REQUEST_CLASS = "/detail/social";
    const ARRAY_LIMIT = 500;
    
    protected function newResultSet() {
        return new Result($this->client->getTempDir());
    }
}

