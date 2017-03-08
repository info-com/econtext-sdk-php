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
    private $tempDir = null;
    private $tempSubDir = null;

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
        $this->setBaseUri($baseuri);
        $this->guzzleClient = new GuzzleHttp\Client([
            "base_uri" => $this->baseuri,
            "auth" => [$this->username, $this->password],
            "http_errors" => false,
            "headers" => [
                "User-Agent" => "eContext API Client (PHP/1.0)",
                "Content-Type" => "application/json"
            ],
        ]);
        $this->checkLogin();
        $this->statusCallback = $statusCallback;
	}

    /**
     * If we created a subdirectory for temporary files - remove them when we're done
     */
	public function __destruct() {
	    unset($this->guzzleClient);
        if($this->tempSubDir !== null) {
            rmdir($this->tempSubDir);
        }
    }

    public function setBaseUri($baseuri="https://api.econtext.com") {
	    $this->baseuri = $baseuri;
	    return $this;
    }

    public function getBaseUri() {
	    return $this->baseuri;
    }

    /**
     * Set the location of the directory where temporary files should be stored
     *
     * @param $tempDir string A path to a tempDirectory
     */
	public function setTempDir($tempDir) {
	    if(is_dir($tempDir)) {
	        $dir = $tempDir . '/econtext-'.\uniqid() . '/';
	        if(mkdir($dir)) {
	            $this->tempSubDir = $dir;
	            $this->tempDir = $dir;
            }
            else {
                $this->tempDir = $tempDir;
            }
        }
    }

    public function getTempDir() {
	    if($this->tempDir == null) {
	        $this->tempDir = $this->createDefaultTempDir();
        }
	    return $this->tempDir;
    }

    /**
     * Create a temporary directory to store result files.  If we are unable to create a temporary directory, result
     * files will be written directly to the system temp dir (supplied by sys_get_temp_dir) and we won't attempt to
     * remove it when we're done.
     *
     * @return null|string The filepath to the temp directory if we were able to create it.
     */
    private function createDefaultTempDir() {
        $dir = \sys_get_temp_dir() . '/econtext-' . \uniqid() . "/";
        if(mkdir($dir)) {
            return $dir;
        }
        return null;
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
            'fulfilled' => function ($response, $index) use (&$resultSet) {
                $resultSet->addResultSet((string)$response->getBody(), $index);
                if(is_callable($this->statusCallback)) {
                    $this->statusCallback->__invoke($index, $response);
                }
            },
            'rejected' => function ($reason, $index) use (&$resultSet) {
                // this shouldn't get called, because the client has turned off http_errors...
                $response = $reason->getResponse();
                if($reason->getCode() == 503) {
                    $body = '{"econtext": {"error": {"message": "503 Service Temporarily Unavailable", "code": 503}}}';
                } else {
                    $body = $response->getBody();
                }
                $resultSet->addResultSet((string)$body, $index);
                if(is_callable($this->statusCallback)) {
                    $this->statusCallback->__invoke($index, $response);
                }
            },
        ]);
        $promise = $pool->promise();
        $promise->wait();
        return $resultSet;
    }
}

