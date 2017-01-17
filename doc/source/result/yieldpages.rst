yieldPages
==========

Description
^^^^^^^^^^^

.. code-block:: php

    public generator Result::yieldPages( void )

A generator which loads a page and returns the body content of each page.  Each iteration resets the contents of the
Result, which means that :doc:`getbody` and :doc:`getcurrentpage` and other items will be updated.

Parameters
^^^^^^^^^^

This function has no parameters

Return Values
^^^^^^^^^^^^^

Returns a generator that yields the result of :doc:`getbody` with each iteration, after having loaded the next eContext
API Result page.

Example
^^^^^^^

Example #1
""""""""""

Check the page number for each item yielded from the generator

.. code-block:: php

    $client = new eContext\Client(ECONTEXT_USERNAME, ECONTEXT_PASSWORD);
    $url = new eContext\Classify\Url($client);
    $urls = ['http://www.cnn.com', 'http://www.econtext.ai', 'http://www.nytimes.com'];
    $url->setData($urls);
    $results = $url->classify(3); # classify all pages at once
    foreach($results->yieldPages() as $page_body) {
        echo "page " . $results->getCurrentPage() . " --> " . $urls[$results->getCurrentPage() - 1]. PHP_EOL;
    }

Should output the following: ::

    page 1 --> http://www.cnn.com
    page 2 --> http://www.econtext.ai
    page 3 --> http://www.nytimes.com
