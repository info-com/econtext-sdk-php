setParameter
============

Set a parameter that will be included in the call to the eContext API.

Description
^^^^^^^^^^^

.. code-block:: php

    public ApiCall ApiCall::setParameter($key, $value)

Parameters
^^^^^^^^^^

$key
    The name of the parameter to set

$value
    The value of the parameter

Return Values
^^^^^^^^^^^^^

Returns the :ref:`api-call` object for method chaining

Examples
^^^^^^^^

Example #1 Set "entities" parameter to ``true``

.. code-block:: php

    $client = new eContext\Client(ECONTEXT_USERNAME, ECONTEXT_PASSWORD);
    $apicall = new eContext\ApiCall($client);
    $apicall->setParameter('entities', true);

