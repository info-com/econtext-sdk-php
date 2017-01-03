A simple PHP client to expose the eContext API.

## Installation

We recommending using Composer to include the eContext library in your project.
In the composer.json file for your project you can simply include 
econtext-sdk-php and Composer will handle downloading and including the most
recent version for you.

{
   "require": {
      "info-com/econtext-sdk-php": "0.1.*"
   }
}

## Basic Usage
    
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
        "Spacesuit from @MITAeroAstro shrink-wraps to astronauts' bodies",
        "This social post should be flagged for fireworks and gambling references"
    ];
    $client = new eContext\Client($username, $password);
    $classify = new Social($client);
    $classify->setData($posts);
    $classify->setParameter('classification_type', 1);
    $result = $classify->classify();
    
    foreach($result->yieldResults() as $mapping) {
        foreach($mapping['scored_categories'] as $cat_info) {
            $cid = $cat_info['category_id'];
            echo " * {$result->getCategory($cid)['name']}".PHP_EOL;
        }
    }

## eContext\Client

The basic client object that wraps in the GuzzleHttp objects.  It should be passed to each of the calls that are passed
in to the API.

### Parameters

- $username   An API username
- $password   An API password
- $baseuri    The base URI for the API.  By default, this is set to http://api.econtext.com
- $statusCallback A callback which is called after every API interaction.

The statusCallback can be used to interact during a run.  The callback function must receive two parameters.  The first
is an index number corresponding to the sequence id for that call (e.g. $data[4]).  The second is a GuzzleHttp Response
object for the call (this is true if the API call is successful or fails).

## eContext\Classify\Type\Social

A Social Classification object which interacts with the classify/social endpoint.  The data object for the Social object
should be a list of social content phrases (e.g. `["this is my first tweet that I want to classify", "this is my second
tweet that I want to classify", ...]`) and may contain as many elements as you would like.  This list will be broken up
and sent to the API in separate calls.  These calls may be parallelized by setting the `concurrency` parameter in the
$social->classify() call.  For example, if you have 10,000 elements to be classified and set concurrency to 5, you
should be able to run 5 sets of calls at the same time, each sending over 1000 elements at once, so 5,000 concurrently.

When the calls are completed, you can retrieve them in the order that they were submitted using the object returned from
the classify() call.

You may pass in extra parameters to the classification calls as well.  For example, in order to specify that only the
eContext Rule-Based Classifications should be used, you can call `$classify->setParameter('classification_type', 1)`;

## eContext\Classify\Result

The base result object for eContext Classification objects.  Each time a result is yielded in the yieldResults() call, a
Result object is created and populated with several items.  Each of the different classification types extends this
object, but the base object is still created and populated with, at least, `categories`, `translate`, `overlay` and
`inner` parameters.  Individual categories may be retrieved using `$result->getCategory($id)` calls and will return
an associated category array.  The `inner` parameter contains the entire inner contents of the API call, and can be used
for manual exploration of the result object.

## eContext\Classify\Results\Social

The result object for Social classification will yieldResults across all of the input data.  If you input 10,000 tweets
in the data for the `$classify` object, yieldResults should yield 10,000 result arrays.  Each of these arrays will
contain an associated array with the inner contents of the API result.  When a new page is loaded, for example when you
go from post 1000 to post 1001, the object's categories array will change as well to load in the new categories for that
page.  This means that categories that existed on the first page may no longer be available on the second page.

In addition to the `categories`, `inner`, `translate`, and `overlay` parameters stored in the generic Result object, the
Social Result object will also contain a `results` object that corresponds to the items yielded in the yieldResults
call.

## eContext\Classify\Results\Html and eContext\Classify\Results\Url

The results objcet for both HTML and URL classification objects.  Each data object passed in to the either the URL or
HTML classification objects may contain multiple elements.  For example, you may populate the $html_classify object with
HTML content from several different pages at once, or several urls at once.

For example:

```
    $url = new eContext\Classify\Type\Url($client);
    $urls = ['http://www.cnn.com', 'http://www.econtext.ai', 'http://www.nytimes.com'];
    $url->setData($urls);
    $results = $url->classify(3); # classify all pages at once

    $i = 0;
    foreach($results->yieldResults() as $url_result) {
        echo $urls[$i++] . PHP_EOL;
        echo $url_result['title'] . PHP_EOL;
        echo "Categories:" . PHP_EOL;
        foreach($url_result['scored_categories'] as $scored_category) {
            echo "  " . $results->getCategory($scored_category['category_id'])['name'] . " : " . $scored_category['score'] . PHP_EOL
        }
    }
```

The output from the above calls should look like this:

```
http://www.cnn.com
CNN - Breaking News, Latest News and Videos
Categories:
  Newsworthy Topics : 0.75
  Health : 0.125
  Arts & Entertainment : 0.125

http://www.econtext.ai
eContext | The Web's Deepest Text Classification System
Categories:
  eContext : 0.90909090909091
  Business & Industrial : 0.090909090909091

http://www.nytimes.com
The New York Times - Breaking News, World News & Multimedia
Categories:
  The New York Times : 0.23255813953488
  House of Representatives : 0.18604651162791
  Republican Party : 0.093023255813953
  Palliative & End of Life Care : 0.093023255813953
  Paul Ryan : 0.046511627906977
  Newsworthy Topics : 0.046511627906977
  Sports Movies : 0.046511627906977
  Senates : 0.046511627906977
  Physicians : 0.046511627906977
  International News : 0.046511627906977
  Art Museums & Sculpture Gardens : 0.046511627906977
  Republicans in Congress : 0.046511627906977
  United States Armed Forces : 0.023255813953488
```

Note how the $results->getCategories() object changed with each advance through the iterator, and returns the categories
associated with the current page (the current url_result).

Each $url_result object contains three items.

- 'title' -- corresponds to the title element in the HTML/URL page that was retrieved
- 'scored_categories' -- a list of scored_category objects, each of which contains a `category_id` and `score`.
- 'scored_keywords' -- a list of keywords that were of value in the page