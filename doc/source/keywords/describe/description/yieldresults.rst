yieldResults
============

A generator that runs through the keyword description results for a set of keywords.

Description
^^^^^^^^^^^

.. code-block:: php

    public generator Result::yieldResults( void )

Parameters
^^^^^^^^^^

This function has no parameters

Return Values
^^^^^^^^^^^^^

Returns a generator that yields a set of keyword descriptions from the eContext API for each keyword that was submitted.

Typically, each item yielded from this generator should include the following items:

keyword
    The cleaned keyword being described

lexemes_dict
    A dictionary of lexemes where each key is a lexeme and the value is an array of lexeme positions.  The first position
    available is 1.

lexemes_str
    A string representation of the lexemes_dict.  For example, ``hello worlds hellos`` lexeme_str would be
    ``"'world':2 'hello':1,3"``

Example
^^^^^^^

Example #1
""""""""""

Echo the eContext API Category names matched to each input keyword

.. code-block:: php

    $keywords = [
        "hello world",
        "breaking bad",
        "ace hardward chicago",
        "elle magazine covers",
        "macbook pro laptops",
        "chicago blackhawks",
        "arsenal vs barcelona",
        "javascript programming guides",
        "programming games online",
        "coding games online",
        "chicago bears",
        "chicago cubs"
    ];
    $client = new eContext\Client(ECONTEXT_USERNAME, ECONTEXT_PASSWORD);
    $descriptions = new eContext\Keywords\Describe\Describe($client);
    $descriptions->setData($keywords);
    $results = $descriptions->describe();
    foreach($result->yieldResults() as $keywordDescription) {
        echo json_encode($keywordDescription['lexemes_dict']) . PHP_EOL;
    }

Should output the following: ::

    {"world":[2],"hello":[1]}
    {"break":[1],"bad":[2]}
    {"hardward":[2],"ace":[1],"chicago":[3]}
    {"magazine":[2],"cover":[3],"elle":[1]}
    {"pro":[2],"laptop":[3],"macbook":[1]}
    {"blackhawk":[2],"chicago":[1]}
    {"vs":[2],"arsenal":[1],"barcelona":[3]}
    {"javascript":[1],"programming":[2],"guide":[3]}
    {"game":[2],"programming":[1],"online":[3]}
    {"game":[2],"code":[1],"online":[3]}
    {"bear":[2],"chicago":[1]}
    {"cub":[2],"chicago":[1]}

