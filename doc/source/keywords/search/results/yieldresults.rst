yieldResults
============

A generator that runs through the a keyword result set for a query passed into the eContext API

Description
^^^^^^^^^^^

.. code-block:: php

    public generator Results::yieldResults( void )

Parameters
^^^^^^^^^^

This function has no parameters

Return Values
^^^^^^^^^^^^^

Returns a generator that yields a set of keyword results from the eContext API for the query that was submitted.

Typically, each item yielded from this generator should include the following items:

keyword
    A keyword match from the eContext API

volume
    Estimated monthly search volume for the provided kewyord

category_id
    A category id that can be used to retrieve the category information from the result set.

Example
^^^^^^^

Example #1
""""""""""

Echo the eContext API Category names matched to each input keyword

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
        echo $result['keyword'] . " :: " . $results->getCategory($result['category_id'])['name'] . PHP_EOL;
    }

Should output the following: ::

    break bad ::
    breaking bad :: Breaking Bad
    breaking bad ??? ?????? ??????? ??????? :: Breaking Bad
    breaking,bad :: Breaking Bad
    breaking-bad :: Breaking Bad
    breaking.bad ::
    breaks bad ::
    breaking bad cast :: Breaking Bad
    breaking bad season 6? :: Breaking Bad
    breaking bad season 6 :: Breaking Bad
    breaking bad season 4, episode 1 :: Breaking Bad
    how many seasons of breaking bad? :: Breaking Bad
    amc, breaking bad :: Breaking Bad
    breaking bad season 5 :: Breaking Bad
    breaking bad 2017 :: Breaking Bad
    breaking bad : season 4 episode 2 :: Breaking Bad
    breaking bad episodes :: Breaking Bad
    breaking bad season 1 :: Breaking Bad
    breaking bad characters :: Breaking Bad
    cast of breaking bad :: Breaking Bad

