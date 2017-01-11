Installation
============

We recommending using Composer to include the eContext library in your project. In the `composer.json` file for your
project you can simply include `econtext-sdk-php` and Composer will handle downloading and including the most recent
version for you.

.. code-block:: json

    {
       "require": {
          "info-com/econtext-sdk-php": "0.1.*"
       }
    }

All example assume installation via Composer and should include the following autoloader file as follows:

.. code-block:: php

    require_once('vendor/autoload.php');
