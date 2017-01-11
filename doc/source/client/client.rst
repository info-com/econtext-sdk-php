.. _econtext-client:

eContext\\Client
================

The :ref:`econtext-client` object provides interaction to the eContext API via the GuzzleHttp library.  This allows you to
leverage GuzzleHttp's use of promises in order to dramatically increase your throughput to eContext.  The Client also
allows you to setup a callback function which is called after each GuzzleHttp call.  This allows you to interact with
the Responses directly while processing a larger batch of content.

In order to run any of the eContext SDK functions, you will need to have credentials to the eContext API.  Please
contact sales@econtext.com if you have any questions.

.. toctree::
    :caption: Methods
    :maxdepth: 1
    :glob:

    client-*
