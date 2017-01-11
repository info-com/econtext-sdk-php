setData
=======

Set the list of social media posts that will be passed along to the eContext API.

Description
^^^^^^^^^^^

.. code-block:: php

    public Social Social::setData($data)

Parameters
^^^^^^^^^^

$data
    A list of social media (short form) posts that will be classified

Return Values
^^^^^^^^^^^^^

Returns the :ref:`classify-social` object for method chaining

Examples
^^^^^^^^

Example #1
""""""""""

Provide a list of social posts for the client to classify

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
    $classify = new eContext\Social($client);
    $classify->setData($posts);
    $results = $classify->classify();

