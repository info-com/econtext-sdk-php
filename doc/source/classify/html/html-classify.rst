classify
========

Run a set of HTML documents through the eContext API and return classifications.

Description
^^^^^^^^^^^

.. code-block:: php

    public eContext\Classify\Results\Html Html::classify( [$concurrency = 1 [, $params = array() ]] )

Parameters
^^^^^^^^^^

$concurrency
    An optional parameter that specifies how many concurrent asynchronous calls should be run at once.

$params
    A list of key=>value pairs describing :ref:`classify-parameters` to be included with the eContext API call.

Return Values
^^^^^^^^^^^^^

Returns a :ref:`html-results` object containing classifications for the HTML documents specified for this run.

Examples
^^^^^^^^

Example #1
""""""""""

Classify a list HTML documents.

.. code-block:: php

    $html = [
        '<html><head><title>Example HTML page</title></head><body><h1>Classifying an HTML document</h1><p>eContext provides the best classification results available.</p></body></html>',
        '<html><head><title>Nintendo DS</title></head><body><h1>Nintendo DS</h1><p>The Nintendo DS (ニンテンドーDS Nintendō DS?) or simply, DS, is a 32-bit[3] dual-screen handheld game console developed and released by Nintendo</p></body></html>'
    ]
    $client = new eContext\Client(ECONTEXT_USERNAME, ECONTEXT_PASSWORD);
    $classify = new eContext\Url($client);
    $classify->setData($urls);
    $results = $classify->classify();