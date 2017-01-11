yieldResults
============

A generator that runs through the classification results for a set of HTML documents.

Description
^^^^^^^^^^^

.. code-block:: php

    public generator Result::yieldResults( void )

Parameters
^^^^^^^^^^

This function has no parameters

Return Values
^^^^^^^^^^^^^

Returns a generator that yields each individual classification result with each iteration.  This function handles page
reloads in the background and returns each result in FIFO order so that each result lines up with the input.  In the
case that there is an error during classification, each item yielded will contain an empty response.

Typically, each item yielded from this generator should include the following items:

title
    The title, if available, from the HTML title tag

scored_categories
    A list of scored_category objects with the following keys:

    category_id
        A string identifying the eContext API Category for this result

    score
        A numeric score for the identified category

scored_keywords
    A list of ngrams that have been identified as import for this result.  Each scored_keyword object contains the
    following keys:

    keyword
        The identified keyword

    score
        A numeric score describing the importance ratio for this keyword in the text

Example
^^^^^^^

Example #1
""""""""""

Echo the HTML ``<title>`` tag retrieved from each URL

.. code-block:: php

    $urls = [
        'https://www.econtext.ai',
        'http://www.cnn.com/',
        'http://www.bbc.com/sport'
    ];
    $client = new eContext\Client(ECONTEXT_USERNAME, ECONTEXT_PASSWORD);
    $classify = new eContext\Classify\Url($client);
    $classify->setData($urls);
    $results = $classify->classify(2);

    $i = 0;
    foreach($results->yieldResults() as $urlResult) {
        echo "urls[" . $i++ . "] --> {$urlResult['title']}" . PHP_EOL;
    }

The above example should output the following: ::

    urls[0] --> eContext | The Web's Deepest Text Classification System
    urls[1] --> CNN - Breaking News, Latest News and Videos
    urls[2] --> Home - BBC Sport
