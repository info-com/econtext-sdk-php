yieldResults
============

A generator that runs through the category density results for a set of keywords.

Description
^^^^^^^^^^^

.. code-block:: php

    public generator Result::yieldResults( void )

Parameters
^^^^^^^^^^

This function has no parameters

Return Values
^^^^^^^^^^^^^

Returns a generator that yields a set of matched categories from the eContext API for each keyword that was submitted.

Example
^^^^^^^

Example #1
""""""""""

Echo the eContext API Category names matched to each input keyword

.. code-block:: php

    $keywords = [
        'hotels',
        'facebook',
        'youtube',
        'craigslist',
        'facebook login',
        'aquarius',
        'google',
        'ebay',
        'yahoo'
    ]
    $client = new eContext\Client(ECONTEXT_USERNAME, ECONTEXT_PASSWORD);
    $density = new eContext\Categories\Density($client);
    $density->setData($keywords);
    $results = $density->search();
    foreach($results->yieldResults() as $keywordCategories) {
        foreach($keywordCategories as $keywordCategory) {
            echo $keywords[$results->getCurrentPage()-1] . " :: " . $keywordCategory['name'] . PHP_EOL;
        }
    }


Should output the following: ::

    hotels :: Hotels & Motels
    hotels :: Cheap Hotels & Motels
    hotels :: Beach Hotels
    hotels :: Hotel Discounts
    hotels :: Hotels in New York
    facebook :: Facebook
    facebook :: Pictures
    facebook :: Facebook Friends
    facebook :: Facebook Apps
    facebook :: Emoticons
    youtube :: YouTube
    youtube :: Movies
    youtube :: Songs
    youtube :: Music & Radio
    youtube :: YouTube Channels
    craigslist :: Craigslist
    craigslist :: Cars
    craigslist :: Jobs
    craigslist :: Classified Ads
    craigslist :: Apartments
    facebook login :: Facebook
    facebook login :: Logins
    facebook login :: General Computer Tips & Information
    facebook login :: Email
    facebook login :: PHP
    aquarius :: Aquarius (Zodiac)
    aquarius :: Zodiac Signs
    aquarius :: Zodiac Compatibility
    aquarius :: Hotpoint Dishwashers
    aquarius :: Pisces (Zodiac)
    google :: Google
    google :: Google Chrome
    google :: Google Maps
    google :: Gmail
    google :: Google Earth
    ebay :: eBay
    ebay :: Ebay Motors
    ebay :: Sell on eBay
    ebay :: Classified Ads
    ebay :: eBay Auctions
    yahoo :: Yahoo!
    yahoo :: Yahoo! Mail
    yahoo :: Yahoo! Answers
    yahoo :: Yahoo! Messenger
    yahoo :: Search Engines & Web Portals
