<?php

namespace eContext\Categories;
use eContext\Categories\Result;
use Zenya\CLI;


if (basename($argv[0]) == basename(__FILE__)) {
    require_once('/Users/jspalink/dev/econtext-api-php-client/vendor/autoload.php');
    #require_once('../../Client.php');
    #require_once('../Classify.php');
    #require_once('../Result.php');
    require_once('/Users/jspalink/dev/zenya-php-lib//src/Zenya/CLI.php');
    require_once('/Users/jspalink/dev/zenya-php-lib/src/Zenya/CLI/Argument.php');
}

class Density {
    
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
        $line = $this->next($input);
        if($line === false) {
            return;
        }
        return $line;
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
            echo static::URL_REQUEST_BASE . "/" . $data . PHP_EOL;
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

function main($username, $password, $keywords) {
    $client = new \eContext\Client($username, $password, "https://api-dev.econtext.com");
    $density = new Density($client);
    $density->setData($keywords);
    $result = $density->search(1); // returns a classify result
    foreach($result->yieldResults() as $categories) {
        $categories = json_encode($categories);
        echo "{$result->getCurrentPage()} .. {$categories}".PHP_EOL;
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
