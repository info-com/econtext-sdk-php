getOverlay
==========

Description
^^^^^^^^^^^

.. code-block:: php

    public array Result::getOverlay( string $categoryId , string $overlayId )

Retrieve an eContext API Category overlay array from a result object

.. note::
    Your account must be setup to provide overlay results.  Please contact sales@econtext.com for more information.

Parameters
^^^^^^^^^^

$categoryId
    The ID associated with the category that you wish to retrieve

$overlayId
    The ID associated with the overlay that you wish to retrieve

Return Values
^^^^^^^^^^^^^

Returns an array representation of an eContext API overlay object

Example
^^^^^^^

Example #1
""""""""""

Echo the IAB overlay name for a single keyword, "abba hair care products"

.. code-block:: php

    $classify = new eContext\Classify\Keywords($client, ["abba hair care products"]);
    $results = $classify->classify();

    foreach($results->yieldResults() as $keywordResult) {
        $categoryId = $keywordResult['category_id'];
        $iabCategories = $results->getOverlay($categoryId, "iab2016");
        echo "IAB categories are " . json_encode($iabCategories) . PHP_EOL;
    }

The above code should output::

    IAB categories are [["Style & Fashion","Beauty"]]


