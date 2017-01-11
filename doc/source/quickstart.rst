Quickstart
==========

.. code-block:: php

    $keywords = [
        "hello world",
        "breaking bad characters",
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
    $classify = new eContext\Classify\Keywords($client);
    $classify->setData($keywords);
    $results = $classify->classify();

    $i = 0;
    foreach($results->yieldResults() as $x) {
      echo $posts[$i++] . ' :: ' . $results->getCategory($x['category_id'])['name'] . PHP_EOL;
    }

The above example quickly classifies a short list of keywords, and outputs the keyword and the category name that is
most closely associated with that keyword.  Keyword classification uses the eContext API to find the mostly likely
single category to match a given input.