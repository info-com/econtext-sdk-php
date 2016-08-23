<?php

namespace eContext;
use GuzzleHttp;
use eContext\File;
use eContext\Classify\Result;

class Client {
    
    const JSON_OUTER_ELEMENT = "econtext";
	
	private $username;
	private $password;
    private $baseuri;
    private $guzzleClient;
	
	public function __construct($username, $password, $baseuri="https://api.econtext.com") {
		$this->username = $username;
		$this->password = $password;
        $this->baseuri = $baseuri;
        $this->guzzleClient = new GuzzleHttp\Client([
            "base_uri" => $this->baseuri,
            "auth" => [$this->username, $this->password],
            "headers" => [
                "User-Agent" => "eContext API Client (PHP/1.0)",
                "Content-Type" => "application/json"
            ],
        ]);
	}
    
    public function getGuzzleClient() {
        return $this->guzzleClient;
    }
    
    /**
     * Run a dataset through the eContext Classification Engine using multiple
     * requests at once, using Guzzle's Pool functionality to achieve 
     * concurrency.  This call only works for classification calls - URIs in the
     * API beginning with /v2/classify
     * 
     * @param array|\eContext\File\File $input
     * @param string $type
     * @param string $uri
     * @param int $concurrency
     * 
     */
    public function runPool($yieldAsyncCalls, $resultSet=None, $concurrency=1) {
        $pool = new \GuzzleHttp\Pool($this->guzzleClient, $yieldAsyncCalls, [
            'concurrency' => $concurrency,
            'fulfilled' => function ($response, $index) use ($resultSet) {
                echo "received response for {$index}".memory_get_usage().PHP_EOL;
                $resultSet->addResultSet((string)$response->getBody(), $index);
                #print_r((string)$response->getBody());
            },
            'rejected' => function ($reason, $index) {
                echo "{$index} OOPS - $reason".PHP_EOL;
            },
        ]);
        $promise = $pool->promise();
        $promise->wait();
        return $resultSet;
    }
}

