__construct
===========

Description
^^^^^^^^^^^

.. code-block:: php

    public Client::__construct($username, $password [, $statusCallback = null [, $baseuri = 'https://api.econtext.com' ]] )

Parameters
^^^^^^^^^^

$username
    An eContext username used to access the API.

$password
    An eContext password associated with your account.

$statusCallback
    An *optional* callback function which is called after each GuzzleHttp request when the Client runs the runPool
    method. The callback expects two parameters: ``$index`` and integer identifying the sequence number for the call and a
    ``$response`` which is a ``GuzzleHttp\Response`` object.  Following is an example that simply echos the sequence
    number, and counts the number of errors that have occurred (if any):

    .. code-block:: php

        $errors = 0;
        $total_calls = 0;

        $statusCallback = function($index, $response) use (&$errors, &$total_calls) {
            if($response->getStatusCode() >= 400) {
                $errors++;
            }
            echo ++$total_calls . " API calls completed" . PHP_EOL;
        };

        $client = new eContext\Client(ECONTEXT_USERNAME, ECONTEXT_PASSWORD, $statusCallback);
        $classify->classify();

$baseuri
    An *optional* parameter to specify a different base URI for the eContext API.  For most users, this parameter should
    never be used.  If you have a use-case, please contact eContext to discuss the need for a dedicated environment.

Return Values
^^^^^^^^^^^^^

Returns a new Client instance.