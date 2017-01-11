setParameter
============

Set a parameter that will be included in the call to the eContext API.

Description
^^^^^^^^^^^

.. code-block:: php

    public Text Text::setParameter($key, $value)

Parameters
^^^^^^^^^^

$key
    The name of the :ref:`classify-parameters` to set.

$value
    The value of the parameter

Return Values
^^^^^^^^^^^^^

Returns the :ref:`classify-text` object for method chaining

Examples
^^^^^^^^

Example #1
""""""""""

Set "flags" parameter to ``true``

.. code-block:: php

    $client = new eContext\Client(ECONTEXT_USERNAME, ECONTEXT_PASSWORD);
    $classify = new eContext\Text($client);
    $classify->setParameter('flags', true);

