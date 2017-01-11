classify
========

Run a set of URLs through the eContext API and return classifications.

Description
^^^^^^^^^^^

.. code-block:: php

    public eContext\Classify\Results\Html Url::classify( [$concurrency = 1 [, $params = array() ]] )

Parameters
^^^^^^^^^^

$concurrency
    An optional parameter that specifies how many concurrent asynchronous calls should be run at once.

$params
    A list of key=>value pairs describing :ref:`classify-parameters` to be included with the eContext API call.

Return Values
^^^^^^^^^^^^^

Returns a :ref:`html-results` object containing classifications for the text specified for this run.

.. note::
    URL classification shares the same :ref:`html-results` class as HTML classification.

Examples
^^^^^^^^

Example #1
""""""""""

Classify a list URLs in parallel using two concurrent calls.

.. code-block:: php

    $urls = [
        'https://www.econtext.ai',
        'http://www.cnn.com/',
        'http://www.bbc.com/sport'
    ]
    $client = new eContext\Client(ECONTEXT_USERNAME, ECONTEXT_PASSWORD);
    $classify = new eContext\Url($client);
    $classify->setData($urls);
    $results = $classify->classify(2);