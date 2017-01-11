setParameters
=============

Set parameters that will be included in the call to the eContext API.

Description
^^^^^^^^^^^

.. code-block:: php

    public ApiCall ApiCall::setParameters(array $parameters = array())

Parameters
^^^^^^^^^^

$parameters
    A list of key=>value pairs describing parameters to be included with the eContext API call.

Return Values
^^^^^^^^^^^^^

Returns the :ref:`api-call` object for method chaining

Examples
^^^^^^^^

Example #1 Set "entities" parameter to ``true``

.. code-block:: php

    $client = new eContext\Client(ECONTEXT_USERNAME, ECONTEXT_PASSWORD);
    $apicall = new eContext\ApiCall($client);
    $apicall->setParameters(['entities' => true]);

