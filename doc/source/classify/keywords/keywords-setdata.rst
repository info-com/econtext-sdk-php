setData
=======

Set the data that will be passed along to the eContext API.

Description
^^^^^^^^^^^

.. code-block:: php

    public Keywords Keywords::setData($data)

Parameters
^^^^^^^^^^

$data
    A list of keywords that will be classified

Return Values
^^^^^^^^^^^^^

Returns the :ref:`classify-keywords` object for method chaining

Examples
^^^^^^^^

Example #1
""""""""""

Provide a list of keywords for the client to classify

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
        'yahoo',
        'yahoo mail',
        'gmail',
        'hotmail',
        'mapquest',
        'you tube',
        'amazon',
        'facebook.com',
        'google maps',
        'walmart',
        'yahoo.com',
        'home depot',
        'hotmail.com',
        'pthc',
        'msn',
        'backpage',
        'amazon.com',
        'netflix',
        'aol',
        'weather',
        'cnn',
        'lowes',
        'aol.com',
        'best buy',
        'dogpile',
        'google.com',
        'target',
        'cars',
        'google search',
        'espn',
        'wikipedia',
        'hot',
        'fox news',
        'ls island',
        'sears',
        'nudist',
        'ebay.com',
        'bing',
        'gmail.com',
        'white pages',
        'craigs list',
        'youtube.com'
    ]
    $client = new eContext\Client(ECONTEXT_USERNAME, ECONTEXT_PASSWORD);
    $classify = new eContext\Keywords($client);
    $classify->setData($keywords);
    $results = $classify->classify();

