<?php

namespace eContext\Keywords\Describe;
use eContext\ApiCall;

class Describe extends ApiCall {
    
    const JSON_INNER_ELEMENT = "keywords";
    const URL_REQUEST_BASE = "/v2/keywords/describe";
    const ARRAY_LIMIT = 1000;
    
    protected $method;
    protected $data;
    protected $result;
    protected $request;
    protected $response;
    protected $requestUrl;
    protected $start;
    protected $client;
    protected $input;
    
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
            $input = $this->input;
            $input['async'] = false;
            $input["keywords"] = $data;
            $body = \GuzzleHttp\Psr7\stream_for(json_encode($input));
            yield $i++ => function() use ($body, $data) {
                return $this->client->getGuzzleClient()->postAsync(static::URL_REQUEST_BASE, ['body' => \GuzzleHttp\Psr7\stream_for($body)]);
            };
        }
    }
    
    /**
     * Create a new result set
     * 
     * @return \eContext\Classify\Result
     */
    protected function newResultSet() {
        return new Description();
    }
    
    /**
     * Describe the content
     * 
     * @param int $concurrency How large of pool to use
     * @param array $params A dictionary of base parameters to pass into the classification call (e.g. ['flags'=>true])
     * @throws \Exception You gotta have a requestUrl and data
     * @return \eContext\Result A Result Set
     */
    public function describe($concurrency=1, array $params=array()) {
        if($this->data == null) {
            throw new \Exception("Can't describe an empty dataset");
        }
        if(!empty($params)) {
            $this->input = $params;
        }
        $resultSet = $this->client->runPool($this->yieldAsyncCalls(), $this->newResultSet(), $concurrency);
        return $resultSet;
    }
}