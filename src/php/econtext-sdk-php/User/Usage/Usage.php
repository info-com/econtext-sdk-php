<?php
/**
 * Created by PhpStorm.
 * User: jspalink
 * Date: 4/28/17
 * Time: 10:25 AM
 */

namespace eContext\User\Usage;


use eContext\ApiCall;
use eContext\Client;
use eContext\Result;

class Usage extends ApiCall {
    
    const JSON_INNER_ELEMENT = "user";
    const URL_REQUEST_BASE = "/v2/user/usage";
    const ARRAY_LIMIT = 1;
    
    public function yieldAsyncCalls() {
        throw new \Exception('Not implemented');
    }
    
    /**
     * Describe the content
     *
     * @param array $params A dictionary of base parameters to pass into the classification call (e.g. ['flags'=>true])
     * @throws \Exception You gotta have a requestUrl and data
     * @return array An object describing a users usage
     */
    public function usage(array $params=array()) {
        $response = $this->client->getGuzzleClient()->request('GET', self::URL_REQUEST_BASE, ['query' => $params]);
        $result = new Result();
        $body = json_decode($response->getBody(), true);
        $result->setBody($body);
        if($result->hasError()) {
            throw new \Exception($result->getErrorMessage(), $result->getErrorCode());
        }
        $data = $result->getBody();
        return $result->get(self::JSON_INNER_ELEMENT, $data[Client::JSON_OUTER_ELEMENT], array());
    }
}