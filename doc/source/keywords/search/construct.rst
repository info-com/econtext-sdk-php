__construct
===========

Description
^^^^^^^^^^^

.. code-block:: php

    public Search::__construct( Client $client [, array $data = null ] )

Parameters
^^^^^^^^^^

$client
    An :ref:`econtext-client` object which is used to perform interaction with the eContext API

$data
    An optional parameter for the constructor.  A keyword search array describing the search phrase and optional filters
    that may be included in a search.  Information about keyword search queries may be found in the `eContext API
    Keyword Search documentation`_

Return Values
^^^^^^^^^^^^^

Returns a new Search instance.

.. _eContext API Keyword Search documentation: http://econtext-api.readthedocs.io/en/stable/keywords-search.html