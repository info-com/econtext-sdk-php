<?php
/**
 * Created by PhpStorm.
 * User: jspalink
 * Date: 5/21/18
 * Time: 1:23 PM
 */

namespace eContext\Classify\Detail;
use eContext\Classify\Classify;


class Url extends Classify {
    
    const CLASSIFY_TYPE = "url";
    const URL_REQUEST_CLASS = "/detail/url";
    const ARRAY_LIMIT = 1;
    
    protected function newResultSet() {
        return new Result($this->client->getTempDir());
    }
}
