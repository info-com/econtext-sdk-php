search
======

Run a set of keywords through the eContext API Categories Density search and return a result set

Description
^^^^^^^^^^^

.. code-block:: php

    public eContext\Categories\Result Density::search( [ $concurrency = 1 ] )

Parameters
^^^^^^^^^^

$concurrency
    An optional parameter that specifies how many concurrent asynchronous calls should be run at once.

Return Values
^^^^^^^^^^^^^

Returns a :ref:`density-results` object containing possible eContext API Category matches for the keywords specified in
this run.

Examples
^^^^^^^^

Example #1
""""""""""

Provide a list of keywords for the client to search using Categories Density search

.. code-block:: php

    $keywords = [
        'hotels',
        'facebook',
        'youtube',
        'craigslist',
        'facebook login',
        'aquarius',
        'google',
        'ebay',
        'yahoo'
    ]
    $client = new eContext\Client(ECONTEXT_USERNAME, ECONTEXT_PASSWORD);
    $density = new eContext\Categories\Density($client);
    $density->setData($keywords);
    $results = $density->search();

