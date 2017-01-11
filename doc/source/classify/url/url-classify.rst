classify
========

Run a set of long-form text posts through the eContext API and return classifications.

Description
^^^^^^^^^^^

.. code-block:: php

    public eContext\Classify\Results\Text Text::classify( [$concurrency = 1 [, $params = array() ]] )

Parameters
^^^^^^^^^^

$concurrency
    An optional parameter that specifies how many concurrent asynchronous calls should be run at once.

$params
    A list of key=>value pairs describing :ref:`classify-parameters` to be included with the eContext API call.

Return Values
^^^^^^^^^^^^^

Returns a :ref:`text-results` object containing classifications for the text specified for this run.

Examples
^^^^^^^^

Example #1
""""""""""

Classify a list of text posts in parallel using three concurrent calls.

.. code-block:: php

    $posts = [
        'HAPPY PI DAY 3.141592653589793238462643383279502884197169399375...',
        'Happy birthday, MIT! The Institute was founded April 10, 1861 by William Barton Rogers. #tbt',
        'Consciousness is a state of matter',
        'Flying car by @MITAeroAstro spinoff @Terrafugia moves from science fiction to reality',
        'Amazing Time-Lapse Video Shows Evolution of #Universe Like Never Before',
        'MIT alum @JeopardyJulia now trails only @kenjennings for all-time #Jeopardy! wins',
        'Happy b-day Nikola #Tesla! Startup @WiTricity is bringing his ideas on wireless power to life',
        'Seen at the Student Center this afternoon: Tetris hash browns!',
        'Researchers at @eapsMIT say a large earthquake may occur 5 miles from Istanbul',
        'MIT\'s robotic cheetah can now run and jump untethered',
        'Spacesuit from @MITAeroAstro shrink-wraps to astronauts\' bodies'
    ]
    $client = new eContext\Client(ECONTEXT_USERNAME, ECONTEXT_PASSWORD);
    $classify = new eContext\Text($client);
    $classify->setData($posts);
    $results = $classify->classify(3);