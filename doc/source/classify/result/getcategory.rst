getCategory
===========

Description
^^^^^^^^^^^

.. code-block:: php

    public array Result::getCategory( string $categoryId )

Retrieve a Category array from a result object

Parameters
^^^^^^^^^^

$categoryId
    The ID associated with the category that you wish to retrieve

Return Values
^^^^^^^^^^^^^

Returns an array representation of an eContext API Category object

Example
^^^^^^^

Example #1
""""""""""

Echo the category name for a single keyword, "breaking bad"

.. code-block:: php

    $classify = new eContext\Classify\Keywords($client, ["breaking bad"]);
    $results = $classify->classify();

    foreach($results->yieldResults as $keywordResult) {
        $categoryId = $keywordResult['category_id'];
        $category = $results->getCategory($categoryId);
        echo "Retrieved category " . $category['name'] . PHP_EOL;
    }

The above code should output::

    Retrieved category Breaking Bad
