<?php

namespace eContext\Keywords\Describe;
use eContext\Result;
use Zenya\CLI;


if (basename($argv[0]) == basename(__FILE__)) {
    require_once('/Users/jspalink/dev/econtext-api-php-client/vendor/autoload.php');
    #require_once('../../Client.php');
    #require_once('../Classify.php');
    #require_once('../Result.php');
    require_once('/Users/jspalink/dev/zenya-php-lib//src/Zenya/CLI.php');
    require_once('/Users/jspalink/dev/zenya-php-lib/src/Zenya/CLI/Argument.php');
}

class Describe {
    
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
    
    public function __construct($client=null, $data=null) {
        $this->client = $client;
        $this->input = array();
    }
    
    public function setData($data) {
        $this->data = $data;
        return $this;
    }
    
    public function setParameters(array $parameters=array()) {
        $this->input = $parameters;
    }
    
    public function setParameter($key, $value) {
        $this->input[$key] = $value;
    }
    
    /**
     * If $iterable is a File, yield each line, else yield each item in the
     * array.  This allows us to go through a file, or a list pretty easily.
     * 
     * @param type $iterable
     */
    protected function next(&$iterable) {
        if(is_a($iterable, "\eContext\File\File")) {
            return $iterable->readline();
        } else {
            $n = current($iterable);
            next($iterable);
            return $n;
        }
    }
    
    /**
     * Chunk the data
     * @param mixed $input
     * @return mixed
     */
    protected function chunkData(&$input) {
        $data = array();
        while (count($data) < static::ARRAY_LIMIT and ($line = $this->next($input)) !== false) {
            $data[] = $line;
        }
        if(count($data) == 0) {
            return;
        }
        return $data;
    }
    
    /**
     * Yield Guzzle client promises
     * 
     * @param \eContext\File\File|array $input
     * @param string $type The type key explaining the data going into the eContext API (e.g. "social", "keywords", "url", "html", "text")
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    protected function yieldAsyncCalls() {
        $i = 0;
        while(true) {
            $data = $this->chunkData($this->data);
            if($data == false) {
                return;
            }
            echo static::URL_REQUEST_BASE . PHP_EOL;
            $input = $this->input;
            $input['async'] = false;
            $input["keywords"] = $data;
            print_r($input);
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
            throw new \Exception("Can't classify an empty dataset");
        }
        if(!empty($params)) {
            $this->input = $params;
        }
        $resultSet = $this->client->runPool($this->yieldAsyncCalls(), $this->newResultSet(), $concurrency);
        return $resultSet;
    }
}

function main($username, $password, $keywords) {
    $client = new \eContext\Client($username, $password, "https://api-dev.econtext.com");
    $describe = new Describe($client);
    $describe->setData($keywords);
    $result = $describe->describe(1); // returns a classify result
    foreach($result->yieldResults() as $description) {
        $description = json_encode($description);
        echo "{$result->getCurrentPage()} .. {$description}".PHP_EOL;
    }
}

if (basename($argv[0]) == basename(__FILE__)) {
    $a = new CLI("Run a list of keywords to describe.");
    $a->addArg("i", "input", "input", "input", true, null, "store", "string", "Comma separated list of keywords");
    $a->addArg("u", "username", "username", "username", true, null, "store", "string", "eContext API Username");
    $a->addArg("p", "password", "password", "password", true, null, "store", "string", "eContext API Password");
    $args = $a->parse();
    $keywords = explode(",", $a->getArg('input')->getValue());
    $username = $a->getArg('username')->getValue();
    $password = $a->getArg('password')->getValue();
    main($username, $password, $keywords);
}
