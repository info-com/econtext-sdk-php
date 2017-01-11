__construct
===========

The ApiCall base class.  Functions that interact with the eContext API should extend this class.

Description
^^^^^^^^^^^

.. code-block:: php

    public ApiCall::__construct($client [, $data = null ] )


Parameters
^^^^^^^^^^

$client
    An :ref:`econtext-client` object which is used to perform interaction with the eContext API

$data
    An optional parameter for the constructor.  The data that will be passed into the eContext API

Return Values
^^^^^^^^^^^^^

Returns a new ApiCall instance.