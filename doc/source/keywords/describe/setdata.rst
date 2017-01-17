setData
=======

Set the data that will be passed along to the eContext API.

Description
^^^^^^^^^^^

.. code-block:: php

    public Describe Describe::setData( $data )

Parameters
^^^^^^^^^^

$data
    A list of keywords that will be classified

Return Values
^^^^^^^^^^^^^

Returns the :ref:`keywords-describe` object for method chaining

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
    $describe = new eContext\Kewyords\Describe($client);
    $describe->setData($keywords);
    $results = $describe->$describe();

