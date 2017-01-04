<?php

namespace eContext\Categories;
use eContext\ApiCall;

class Density extends ApiCall {
    
    const JSON_INNER_ELEMENT = "categories";
    const URL_REQUEST_BASE = "/v2/categories/search";
    const ARRAY_LIMIT = 1;
    
    protected $method;
    protected $data;
    protected $result;
    protected $request;
    protected $response;
    protected $requestUrl;
    protected $start;
    protected $client;
    protected $input;
    
    public function __construct($client=null, $data=null) {
        $this->client = $client;
    }
    
    public function setData($data) {
        $this->data = $data;
        return $this;
    }
    
    /**
     * Yield Guzzle client promises
     * 
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    protected function yieldAsyncCalls() {
        $i = 0;
        while(true) {
            $data = $this->chunkData($this->data);
            if($data == false) {
                return;
            }
            yield $i++ => function() use ($data) {
                return $this->client->getGuzzleClient()->getAsync(static::URL_REQUEST_BASE . "/" . $data);
            };
        }
    }
    
    /**
     * Create a new result set
     * 
     * @return \eContext\Classify\Result
     */
    protected function newResultSet() {
        return new Result();
    }
    
    /**
     * Describe the content
     * 
     * @param int $concurrency How large of pool to use
     * @param array $params A dictionary of base parameters to pass into the classification call (e.g. ['flags'=>true])
     * @throws \Exception You gotta have a requestUrl and data
     * @return \eContext\Result A Result Set
     */
    public function search($concurrency=1) {
        if($this->data == null) {
            throw new \Exception("Can't classify an empty dataset");
        }
        $resultSet = $this->client->runPool($this->yieldAsyncCalls(), $this->newResultSet(), $concurrency);
        return $resultSet;
    }
}