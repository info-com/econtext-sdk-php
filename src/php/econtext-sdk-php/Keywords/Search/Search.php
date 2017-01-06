<?php

namespace eContext\Keywords\Search;
use eContext\ApiCall;
use eContext\Client;

class Search extends ApiCall {

    const JSON_INNER_ELEMENT = "keywords";
    const URL_REQUEST_BASE = "/v2/keywords/search";
    const ARRAY_LIMIT = 1;

    protected $searchUri;
    protected $pages;

    protected function chunkData(&$input) {
        return $input;
    }

    /**
     * Yield Guzzle client promises
     *
     * @throws \Exception
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    protected function yieldAsyncCalls() {
        $search = $this;
        foreach($this->pages as $pageId) {
            yield $pageId-1 => function() use ($search, $pageId) {
                return $this->client->getGuzzleClient()->getAsync("{$search->searchUri}?page={$pageId}");
            };
        }
    }

    /**
     * Create a new result set
     *
     * @return \eContext\Keywords\Search\Results
     */
    protected function newResultSet() {
        return new Results();
    }

    /**
     * Describe the content
     *
     * @param int $concurrency How large of pool to use
     * @param array $params A dictionary of base parameters to pass into the search call (e.g. ['flags'=>true])
     * @throws \Exception You gotta have a requestUrl and data
     * @return \eContext\Result A Result Set
     */
    public function search($concurrency=1, array $params=array()) {
        if($this->data == null) {
            throw new \Exception("Need a search phrase to look for");
        }
        $input = $this->data;
        if(!empty($params)) {
            foreach($params as $k=>$v) {
                $input[$k] = $v;
            }
        }
        $body = \GuzzleHttp\Psr7\stream_for(json_encode($input));
        # Initial call to get info on the search (number of results, pages, result uri, etc)
        $result = $this->client->getGuzzleClient()->post(static::URL_REQUEST_BASE, ['body' => \GuzzleHttp\Psr7\stream_for($body)]);
        $response = json_decode($result->getBody(), true);
        if($result->getStatusCode() !== 200) {
            throw new \Exception("An error occurred");
        }
        $this->searchUri = $response[Client::JSON_OUTER_ELEMENT][self::JSON_INNER_ELEMENT]['result_uri'];
        $this->pages = range(1, $response[Client::JSON_OUTER_ELEMENT][self::JSON_INNER_ELEMENT]['pages']);
        $resultSet = $this->client->runPool($this->yieldAsyncCalls(), $this->newResultSet(), $concurrency);
        return $resultSet;
    }

}