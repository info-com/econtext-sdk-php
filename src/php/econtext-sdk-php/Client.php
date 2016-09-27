<?php

namespace eContext;
use GuzzleHttp;

class Client {
    
    const JSON_OUTER_ELEMENT = "econtext";
	
	private $username;
	private $password;
    private $baseuri;
    private $guzzleClient;
    protected $statusCallback;
	
	public function __construct($username, $password, $baseuri="https://api.econtext.com", $statusCallback=null) {
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
        $this->statusCallback = $statusCallback;
	}
    
    /**
     * Sets a callback function which is called after each API request.  You can
     * use this to track progress, stat collection, etc.  The function should
     * accept two parameters - the first is an index number corresponding to the
     * sequence id for that call (e.g. $data[4]).  The second is the Guzzle
     * response object for that call.
     * 
     * @param type $statusCallback
     */
    public function setStatusCallback($statusCallback) {
        $this->statusCallback = $statusCallback;
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
        $client = $this;
        $pool = new \GuzzleHttp\Pool($this->guzzleClient, $yieldAsyncCalls, [
            'concurrency' => $concurrency,
            'fulfilled' => function ($response, $index) use ($resultSet) {
                #echo "received response for {$index} ".memory_get_usage().PHP_EOL;
                $resultSet->addResultSet((string)$response->getBody(), $index);
                if(is_callable($this->statusCallback)) {
                    $this->statusCallback->__invoke($index, $response);
                }
            },
            'rejected' => function ($reason, $index) use ($resultSet) {
                #echo "{$index} OOPS - {$reason->getCode()}".PHP_EOL;
                if($reason->getCode() == 503) {
                    $body = '{"econtext": {"error": {"message": "503 Service Temporarily Unavailable", "code": 503}}}';
                } else {
                    $body = (string)$reason->getResponse()->getBody();
                }
                $resultSet->addResultSet($body, $index);
                if(is_callable($this->statusCallback)) {
                    $this->statusCallback->__invoke($index, $reason->getResponse());
                }
            },
        ]);
        $promise = $pool->promise();
        $promise->wait();
        return $resultSet;
    }
}

