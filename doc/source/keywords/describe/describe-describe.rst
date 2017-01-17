describe
========

Run a set of keywords through the eContext API and return their descriptions

Description
^^^^^^^^^^^

.. code-block:: php

    public eContext\Keywords\Describe\Description Describe::describe( [ $concurrency = 1 ] )

Parameters
^^^^^^^^^^

$concurrency
    An optional parameter that specifies how many concurrent asynchronous calls should be run at once.

Return Values
^^^^^^^^^^^^^

Returns a :ref:`description-description` object containing eContext API keyword descriptions for the provided input

Examples
^^^^^^^^

Example #1
""""""""""

Provide a list of keywords for the client to describe

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
    $describe = new eContext\Keywords\Describe\Describe($client, $keywords);
    $descriptions = $describe->describe();

