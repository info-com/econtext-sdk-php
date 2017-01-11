getTranslate
============

Description
^^^^^^^^^^^

.. code-block:: php

    public array Result::getTranslate( void )

Retrieve a dictionary describing the translation of foreign language content if translation was requested.

Parameters
^^^^^^^^^^

This function has no parameters

Return Values
^^^^^^^^^^^^^

Returns an array describing the translation of input content or ``null`` if translation wasn't requested.  Typically
this dicitonary will contain the following data:

translated_chars
    An integer with the number of characters that were translated.  This is useful if you need to keep track of how many
    characters per month you translated with a third party service such as Google Translate or Bing.

translation
    A list of strings translations.  These should be in the same order as the input to the classification call.

source_language
    A list of ISO 639-1 two letter language codes describing the source languages identified in the input content.

provider
    A string describing the translation server that was used.
