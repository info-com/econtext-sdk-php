getCategories
=============

Description
^^^^^^^^^^^

.. code-block:: php

    public array Result::getCategories( void )

Retrieve the entire Categories array from the eContext API Result object

Parameters
^^^^^^^^^^

This function has no parameters

Return Values
^^^^^^^^^^^^^

Returns an array representation of an eContext API Categories dictionary

Example
^^^^^^^

Example #1
""""""""""

Echo a JSON object listing eContext API Category ID strings associated with the following keywords.

.. code-block:: php

    $classify = new eContext\Classify\Keywords($client, ["breaking bad", "chicago cubs"]);
    $results = $classify->classify();

    foreach($results->yieldPages as $resultPage) {
        echo "Retrieved categoryIds: " . json_encode(array_keys($results->getCategories)) . PHP_EOL;
    }

The above code should output::

    Retrieved categoryIds: ["ac0fb32ea52f2c1228592ad6598c2cc2", "f807a71b4e0687cdaeb24afaaa725781"]
