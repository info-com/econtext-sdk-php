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

    /**
     * Client constructor.
     *
     * @param $username API Username
     * @param $password API Password
     * @param null $statusCallback A callback function which is called after each API request.
     * @param string $baseuri Base uri for the API
     */
	public function __construct($username, $password, $statusCallback=null, $baseuri="https://api.econtext.com") {
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
        $this->checkLogin();
        $this->statusCallback = $statusCallback;
	}

    /**
     * @throws \Exception Fails if credentials are incorrect
     */
	private function checkLogin() {
	    try {
	        $this->guzzleClient->get('v2/user/attributes');
	    } catch (GuzzleHttp\Exception\ClientException $exception) {
	        if($exception->getCode() == '401') {
	            throw new \Exception("Your API login credentials appear invalid.  Please check your username and password and try again");
            }
            throw $exception;
        }
    }
    
    /**
     * Sets a callback function which is called after each API request.  You can
     * use this to track progress, stat collection, etc.  The function should
     * accept two parameters - the first is an index number corresponding to the
     * sequence id for that call (e.g. $data[4]).  The second is the Guzzle
     * response object for that call.
     * 
     * @param callable $statusCallback
     */
    public function setStatusCallback($statusCallback) {
        $this->statusCallback = $statusCallback;
    }

    /**
     * Expose the GuzzleHttp\Client that the eContext\Client makes use
     * of to interact with the system
     * @return GuzzleHttp\Client
     */
    public function getGuzzleClient() {
        return $this->guzzleClient;
    }
    
    /**
     * Run a dataset through the eContext Classification Engine using multiple
     * requests at once, using Guzzle's Pool functionality to achieve 
     * concurrency.  This call only works for classification calls - URIs in the
     * API beginning with /v2/classify
     *
     * @param callable $yieldAsyncCalls A generator to pass into GuzzleHttp\Pool
     * @param Result A result object to save results into
     * @param int $concurrency How many async calls to run in parallel
     * @return Result A result object with saved results
     */
    public function runPool($yieldAsyncCalls, $resultSet=None, $concurrency=1) {
        $pool = new GuzzleHttp\Pool($this->guzzleClient, $yieldAsyncCalls, [
            'concurrency' => $concurrency,
            'fulfilled' => function ($response, $index) use ($resultSet) {
                $resultSet->addResultSet((string)$response->getBody(), $index);
                if(is_callable($this->statusCallback)) {
                    $this->statusCallback->__invoke($index, $response);
                }
            },
            'rejected' => function ($reason, $index) use ($resultSet) {
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

