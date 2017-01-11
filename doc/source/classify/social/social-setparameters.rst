setParameters
=============

Set parameters that will be included in the call to the eContext API.

Description
^^^^^^^^^^^

.. code-block:: php

    public Keywords Keywords::setParameters(array $parameters = array())

Parameters
^^^^^^^^^^

$parameters
    A list of key=>value pairs describing :ref:`classify-parameters` to be included with the eContext API call.

Return Values
^^^^^^^^^^^^^

Returns the :ref:`classify-keywords` object for method chaining

Examples
^^^^^^^^

Example #1
""""""""""

Set "flags" parameter to ``true``

.. code-block:: php

    $client = new eContext\Client(ECONTEXT_USERNAME, ECONTEXT_PASSWORD);
    $classify = new eContext\Classify\Social($client);
    $classify->setParameters(['flags' => true]);

