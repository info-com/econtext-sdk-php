<?php
/**
 * Created by PhpStorm.
 * User: jspalink
 * Date: 5/21/18
 * Time: 1:25 PM
 */

namespace eContext\Classify\Detail;
use eContext\Classify\Classify;

class Text extends Classify {
    
    const CLASSIFY_TYPE = "text";
    const URL_REQUEST_CLASS = "/detail/text";
    const ARRAY_LIMIT = 1;
    
    protected function newResultSet() {
        return new Result($this->client->getTempDir());
    }
}
