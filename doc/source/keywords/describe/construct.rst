__construct
===========

Description
^^^^^^^^^^^

.. code-block:: php

    public Describe::__construct( Client $client [, array $data = null ] )

Parameters
^^^^^^^^^^

$client
    An :ref:`econtext-client` object which is used to perform interaction with the eContext API

$data
    An optional parameter for the constructor.  The list of keywords that will be passed into the eContext API for
    parsing and describing

Return Values
^^^^^^^^^^^^^

Returns a new Describe instance.