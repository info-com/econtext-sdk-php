setData
=======

Set the data that will be passed along to the eContext API.

Description
^^^^^^^^^^^

.. code-block:: php

    public Density Density::setData( $data )

Parameters
^^^^^^^^^^

$data
    A list of keywords that will be classified

Return Values
^^^^^^^^^^^^^

Returns the :ref:`categories-density` object for method chaining

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

