classify
========

Run a set of social posts through the eContext API and return classifications.

Description
^^^^^^^^^^^

.. code-block:: php

    public eContext\Classify\Results\Social Social::classify( [$concurrency = 1 [, $params = array() ]] )

Parameters
^^^^^^^^^^

$concurrency
    An optional parameter that specifies how many concurrent asynchronous calls should be run at once.

$params
    A list of key=>value pairs describing :ref:`classify-parameters` to be included with the eContext API call.

Return Values
^^^^^^^^^^^^^

Returns a :ref:`social-results` object containing classifications for the social posts specified for this run.
