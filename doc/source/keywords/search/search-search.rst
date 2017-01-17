search
======

Run a query through the eContext API and return matching results

Description
^^^^^^^^^^^

.. code-block:: php

    public eContext\Keywords\Search\Results Search::search( [ $concurrency = 1 [, array $params = array() ]] )

Parameters
^^^^^^^^^^

$concurrency
    An optional parameter that specifies how many concurrent asynchronous calls should be run at once.

$params
    Additional parameters to pass in as part of the search query.  These items are merged together with existing data in
    the query.

Return Values
^^^^^^^^^^^^^

Returns a :ref:`search-results` object containing eContext API keyword matches for the provided query

Examples
^^^^^^^^

Example #1
""""""""""

Retrieve the first 20 keywords that match the provided query, limiting page size to 4 keywords per page, and
retrieving them all simultaneously.  Defaults for these values can be found in the `eContext API Keyword Search documentation`_

.. code-block:: php

    $query = [
        'query' => 'breaking bad',
        'type' => 1,
        'limit' => 20,
        'pagesize' => 4
    ];
    $client = new eContext\Client(ECONTEXT_USERNAME, ECONTEXT_PASSWORD);
    $search = new eContext\Keywords\Search\Search($client);
    $search->setData($query);
    $results = $search->search(5); // returns a classify result
    foreach($results->yieldResults() as $result) {
        echo json_encode($result) . PHP_EOL;
    }

Results for the above code will look like this: ::

    {"keyword":"break bad","volume":337500,"category_id":null}
    {"keyword":"breaking bad","volume":337500,"category_id":4077037}
    {"keyword":"breaking bad ??? ?????? ??????? ???????","volume":337500,"category_id":4077037}
    {"keyword":"breaking,bad","volume":337500,"category_id":4077037}
    {"keyword":"breaking-bad","volume":337500,"category_id":4077037}
    {"keyword":"breaking.bad","volume":337500,"category_id":null}
    {"keyword":"breaks bad","volume":337500,"category_id":null}
    {"keyword":"breaking bad cast","volume":55500,"category_id":4077037}
    {"keyword":"breaking bad season 6?","volume":37125,"category_id":4077037}
    {"keyword":"breaking bad season 6","volume":30375,"category_id":4077037}
    {"keyword":"breaking bad season 4, episode 1","volume":20325,"category_id":4077037}
    {"keyword":"how many seasons of breaking bad?","volume":16650,"category_id":4077037}
    {"keyword":"amc, breaking bad","volume":13575,"category_id":4077037}
    {"keyword":"breaking bad season 5","volume":13575,"category_id":4077037}
    {"keyword":"breaking bad 2017","volume":9075,"category_id":4077037}
    {"keyword":"breaking bad : season 4 episode 2","volume":9075,"category_id":4077037}
    {"keyword":"breaking bad episodes","volume":9075,"category_id":4077037}
    {"keyword":"breaking bad season 1","volume":9075,"category_id":4077037}
    {"keyword":"breaking bad characters","volume":7425,"category_id":4077037}
    {"keyword":"cast of breaking bad","volume":7425,"category_id":4077037}

.. _eContext API Keyword Search documentation: http://econtext-api.readthedocs.io/en/stable/keywords-search.html