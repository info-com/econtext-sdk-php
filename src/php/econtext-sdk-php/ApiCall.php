<?php

namespace eContext;
use eContext\File\File;

abstract class ApiCall {

    const ARRAY_LIMIT = 1;

    /**
     * If $iterable is a File, yield each line, else yield each item in the
     * array.  This allows us to go through a file, or a list pretty easily.
     *
     * @param \Iterator $iterable
     * @return mixed the next object in the iteratable
     */
    protected function next(&$iterable) {
        if($iterable instanceof File) {
            return $iterable->readline();
        } else {
            $n = current($iterable);
            next($iterable);
            return $n;
        }
    }

    /**
     * Several of the classification calls require data to be passed in with limitations on batch sizes. This function
     * will use the array limits specified by each of the classification types in order to break up the data input.
     *
     * @param mixed $input
     * @return mixed
     */
    protected function chunkData(&$input) {
        // array limit == 1, then just return the next item
        if(static::ARRAY_LIMIT == 1) {
            return $this->next($input);
        }
        $data = array();
        while(count($data) < static::ARRAY_LIMIT and ($line = $this->next($input)) !== false) {
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
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    abstract protected function yieldAsyncCalls();
}