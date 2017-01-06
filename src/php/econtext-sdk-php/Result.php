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

    /**
     * @var null|array A callSizes array, assigned after an API task to guide in the yieldResults functionality
     */
    protected $callSizes = null;
    
    protected $error = null;
    protected $body = null;
    protected $results = null;

    /**
     * Setup a new Result set
     *
     * @param null|string $tempDir if a tempDir is specified, it should already exist.  Otherwise we'll attempt to create one for you.
     */
    public function __construct($tempDir=null) {
        $this->tempDir = $tempDir != null ?: $this->createDefaultTempDir();
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
     * Unlink tempFiles and remove the tempDir if it was created for this call.
     */
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
        }
        if($index >= count($this->tempFiles)) {
            $this->currentPage = null;
            return null;
        }
        $tempFile = $this->tempFiles[$index];
        $tempFile->open('r');
        $fileContents = array();
        while(($line = $tempFile->readline()) != false) {
            $fileContents[] = $line;
        }
        $tempFile->close();
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

    /**
     * Add call sizes to this result set
     *
     * @param $callSizes
     */
    public function addCallSizes($callSizes) {
        $this->callSizes = $callSizes;
    }

    /**
     * Utility function to get an item from an associative array.
     *
     * @param mixed $needle Search for this key
     * @param array $haystack Search in this associative array
     * @param mixed $default A default value if the key does not exist
     * @return mixed
     */
    public function get($needle, $haystack, $default=null) {
        if(!array_key_exists($needle, $haystack)) {
            return $default;
        }
        return $haystack[$needle];
    }

    /**
     * Iterate through the existing result pages.  This advances the currentPage parameter and reads through saved
     * temp files with result data.  Each time getPage is called (inside this generator) the content of this objects
     * body and error (and possibly other parameters) are updated.
     *
     * @return \Generator
     */
    public function yieldPages() {
        while($this->loadPage(($result = $this->getPage())) !== null) {
            yield $result;
        }
    }

    /**
     * Iterate through a list of social classification results.  Each result
     * will include keyword flags, if requested, NLP entities, if requested, and
     * scored_categories and scored_keywords.
     */
    public function yieldResults() {
        foreach($this->yieldPages() as $result) {
            yield $result;
        }
    }
}