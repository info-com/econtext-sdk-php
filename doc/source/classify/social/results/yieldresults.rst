yieldResults
============

A generator that runs through the classification results for a set of social media posts.

Description
^^^^^^^^^^^

.. code-block:: php

    public generator Result::yieldResults( void )

Parameters
^^^^^^^^^^

This function has no parameters

Return Values
^^^^^^^^^^^^^

Returns a generator that yields each individual classification result with each iteration.  This function handles page
reloads in the background and returns each result in FIFO order so that each result lines up with the input.  In the
case that there is an error during classification, each item yielded will contain an empty response.

Typically, each item yielded from this generator should include the following items:

scored_categories
    A list of scored_category objects with the following keys:

    category_id
        A string identifying the eContext API Category for this result

    score
        A numeric score for the identified category

scored_keywords
    A list of ngrams that have been identified as import for this result.  Each scored_keyword object contains the
    following keys:

    keyword
        The identified keyword

    score
        A numeric score describing the importance ratio for this keyword in the text

Example
^^^^^^^

Example #1
""""""""""

Check the page number for each item yielded from the generator

.. code-block:: php

    $client = new eContext\Client(ECONTEXT_USERNAME, ECONTEXT_PASSWORD);
    $classify = new eContext\Classify\Social($client);
    $posts = [
        "HAPPY PI DAY 3.141592653589793238462643383279502884197169399375...",
        "Happy birthday, MIT! The Institute was founded April 10, 1861 by William Barton Rogers. #tbt",
        "Consciousness is a state of matter",
        "Flying car by @MITAeroAstro spinoff @Terrafugia moves from science fiction to reality",
        "Amazing Time-Lapse Video Shows Evolution of #Universe Like Never Before",
        "MIT alum @JeopardyJulia now trails only @kenjennings for all-time #Jeopardy! wins",
        "Happy b-day Nikola #Tesla! Startup @WiTricity is bringing his ideas on wireless power to life",
        "Seen at the Student Center this afternoon: Tetris hash browns!",
        "Researchers at @eapsMIT say a large earthquake may occur 5 miles from Istanbul",
        "MIT's robotic cheetah can now run and jump untethered",
        "Spacesuit from @MITAeroAstro shrink-wraps to astronauts' bodies"
    ];
    $classify->setData($posts);
    $results = $classify->classify();
    $i = 0;
    foreach($results->yieldResults() as $post_result) {
        echo "post[" . $i++ . "] --> " . json_encode($post_result) . PHP_EOL;
    }

Should output the following: ::

    post[0] --> {"scored_categories":[{"category_id":"122368a0d17e72cd48a7e472aab27d8d","score":1}],"scored_keywords":[{"keyword":"pi day","score":1}],"flags":[],"entities":[]}
    post[1] --> {"scored_categories":[{"category_id":"f7eddf58819869ff51e0d83b6eba4e59","score":0.33333333333333},{"category_id":"a815610c4d2a7ec354611138c4ba1bf1","score":0.33333333333333},{"category_id":"52d90c930eaf27779289164752c62991","score":0.33333333333333}],"scored_keywords":[{"keyword":"birthday","score":0.33333333333333},{"keyword":"tbt","score":0.33333333333333},{"keyword":"mit the institute","score":0.33333333333333}],"flags":[],"entities":[]}
    post[2] --> {"scored_categories":[{"category_id":"7fe320165aad65c827b76b9f4c2d7094","score":1}],"scored_keywords":[{"keyword":"state of matter","score":1}],"flags":[],"entities":[]}
    post[3] --> {"scored_categories":[{"category_id":"27b7db4ac30fe1696b702d8a47ee5cd5","score":0.5},{"category_id":"8a5f896c69fcf9f72d24c9bd169d5283","score":0.5}],"scored_keywords":[{"keyword":"science fiction","score":0.5},{"keyword":"flying car","score":0.5}],"flags":[],"entities":[]}
    post[4] --> {"scored_categories":[{"category_id":"09ab79d1d3d6e83451bcc9433d951690","score":1}],"scored_keywords":[{"keyword":"time-lapse video","score":1}],"flags":[],"entities":[]}
    post[5] --> {"scored_categories":[],"scored_keywords":[],"flags":[],"entities":[]}
    post[6] --> {"scored_categories":[{"category_id":"f7eddf58819869ff51e0d83b6eba4e59","score":1}],"scored_keywords":[{"keyword":"b-day","score":1}],"flags":[],"entities":[]}
    post[7] --> {"scored_categories":[{"category_id":"e3bf891483dbc6cc7b59b7f7afe20a79","score":0.5},{"category_id":"70c85dde424ed666fa044d410b941b48","score":0.5}],"scored_keywords":[{"keyword":"tetris","score":0.5},{"keyword":"hash browns","score":0.5}],"flags":[],"entities":[]}
    post[8] --> {"scored_categories":[{"category_id":"e6c24ce48720b481f29e2232f439effa","score":1}],"scored_keywords":[{"keyword":"large earthquake","score":1}],"flags":[],"entities":[]}
    post[9] --> {"scored_categories":[{"category_id":"db9b57251e0bc41db948ab75272a9392","score":1}],"scored_keywords":[{"keyword":"robotic","score":1}],"flags":[],"entities":[]}
    post[10] --> {"scored_categories":[{"category_id":"80d606580d6ea800a4ba9d3123a11f1c","score":0.5},{"category_id":"0f1e32b2be9761d9ba1b8dc81830a45b","score":0.5}],"scored_keywords":[{"keyword":"spacesuit","score":0.5},{"keyword":"astronauts","score":0.5}],"flags":[],"entities":[]}
