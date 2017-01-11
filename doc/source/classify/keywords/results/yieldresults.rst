yieldResults
============

A generator that runs through the classification results for a set of keywords.

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

flags
    A list of flags that were found with this category

    .. note::
        This field will only be filled if the ``flags`` parameter was set.

category_id
    An identifier for the best fit eContext API Category associated with this keyword

Example
^^^^^^^

Example #1
""""""""""

Echo the eContext API Category name associated with each input keyword

.. code-block:: php

    $keywords = [
        "hello world",
        "breaking bad",
        "ace hardware chicago",
        "elle magazine covers",
        "macbook pro laptops",
        "chicago blackhawks",
        "arsenal vs barcelona",
        "javascript programming guides",
        "programming games online",
        "coding games online",
        "chicago bears",
        "chicago cubs"
    ];
    $client = new eContext\Client(ECONTEXT_USERNAME, ECONTEXT_PASSWORD);
    $classify = new eContext\Classify\Keywords($client, $keywords);
    $results = $classify->classify();

    $i = 0;
    foreach($results->yieldResults() as $keywordResult) {
        $categoryId = $keywordResult['category_id'];
        echo "{$keywords[$i++]} --> " . $results->getCategory($categoryId)['name'] . PHP_EOL;
    }

Should output the following: ::

    hello world -->
    breaking bad --> Breaking Bad
    ace hardware chicago --> Ace Hardware Stores
    elle magazine covers --> Elle Magazine
    macbook pro laptops --> Apple MacBook Pro
    chicago blackhawks --> Chicago Blackhawks
    arsenal vs barcelona --> F.C. Barcelona
    javascript programming guides --> JavaScript Tutorials
    programming games online --> Online Gaming
    coding games online --> Online Gaming
    chicago bears --> Chicago Bears
    chicago cubs --> Chicago Cubs
