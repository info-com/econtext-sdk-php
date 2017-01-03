<?php

namespace eContext;
use eContext\File\Temp;


/**
 * A common interface for paged results.
 * 
 * The result set will use temporary files to store results - this will allow
 * us to send through a large list of keywords or something else, and not 
 * maintain them and all the associated data in memory.  Each time you switch to
 * a new temp file set of results, they will be pulled into memory, and 
 * categories, overlays, and results will be overwritten.
 */
class Result {
        
    /**
     * A path to a temporary directory where result files will be saved.
     * @var string
     */
    private $tempDir = null;
    
    /**
     * Stores a list of paths for individual result files
     * @var \eContext\File\Temp[] A list of Temp files
     */
    private $tempFiles = array();
    
    /**
     * Keep track of what page we're on
     * @var int Current Page of results
     */
    protected $currentPage = 0;
    
    protected $error = null;
    protected $body = null;
    
    
    public function __construct($tempDir=null) {
        $this->tempDir = $tempDir != null ?: $this->createDefaultTempDir();
    }
    
    private function createDefaultTempDir() {
        $dir = \sys_get_temp_dir() . '/econtext-' . \uniqid() . "/";
        if(mkdir($dir)) {
            return $dir;
        }
        return null;
    }
    
    public function __destruct() {
        if($this->tempDir != null) {
            foreach($this->tempFiles as $tempFile) {
                $tempFile->unlink();
            }
            return rmdir($this->tempDir);
        }
    }
    
    public function getCurrentPage() {
        return $this->currentPage;
    }
    
    /**
     * Read in a single page, and return an array with the contents of the page.
     * Assumes that the fileContents will be a serialized JSON object.  If we
     * don't specify a page to retrieve, retrieve the current page, and advance
     * the page counter.
     * 
     * @param type $index
     * @return type
     */
    protected function getPage($index=null) {
        if($index === null) {
            $index = $this->currentPage++;
            #echo "index is null - get new index: {$index}".PHP_EOL;
        }
        if($index >= count($this->tempFiles)) {
            #echo "{$index} >= ".count($this->tempFiles).PHP_EOL;
            $this->currentPage = null;
            return null;
        }
        $tempFile = $this->tempFiles[$index];
        $tempFile->open('r');
        $fileContents = array();
        while(($line = $tempFile->readline()) != false) {
            $fileContents[] = $line;
        }
        return json_decode(implode("\n", $fileContents), true);
    }
    
    protected function loadPage($data) {
        $this->body = $data;
        $this->error = $this->get('error', $data[Client::JSON_OUTER_ELEMENT], null);
        return True;
    }
    
    public function getBody() {
        return $this->body;
    }
    
    public function hasError() {
        return $this->error != null;
    }
    
    public function getErrorMessage() {
        if($this->hasError()) {
            return $this->get('message', $this->error, null);
        }
    }
    
    public function getErrorCode() {
        if($this->hasError()) {
            return $this->get('code', $this->error, null);
        }
    }
    
    /**
     * 
     * @param type $resultSet
     * @param type $index
     */
    public function addResultSet($resultSet, $index=null) {
        $tempFile = new Temp(null, 'c+', $this->tempDir, 'econtext-result', true);
        $tempFile->write($resultSet);
        $tempFile->close();
        if($index !== null) {
            $this->tempFiles[$index] = $tempFile;
        } else {
            $this->tempFiles[] = $tempFile;
        }
    }
    
    public function get($needle, $haystack, $default=null) {
        if(!array_key_exists($needle, $haystack)) {
            return $default;
        }
        return $haystack[$needle];
    }
    
    /**
     * Iterate through a a list of result pages
     */
    public function yieldResults() {
        while(($result = $this->loadPage($this->getPage())) !== null) {
            foreach($this->results as $x) {
                yield $x;
            }
        }
        return;
    }
}