.. _classify-parameters:

Parameters
==========

entities (``bool``)
    Include Entities extracted through traditional NLP techniques in the API output.  Defaults to ``false``

flags (``bool``)
    Identify keywords and phrases that may be sensitive, tagged to the `eContext API Object Flags`_ list.  Defaults to
    ``false``

    .. note::
        Only available in Classify\\Social and Classify\\Keywords classes

best_match (``bool``)
    Reduce the number of categories for each matching n-gram in a single classification operation.  Defaults to ``true``

branches (``array``)
    Provide a list of eContext Category ids to restrict classification to.

    .. note::
        Only available in the Classify\\Keywords class

classification_type (``enum``)
    Manually restrict the classification method.

    =====   ========================================
    Value   Description
    =====   ========================================
    0       Hybrid ML and Rule-based classification
    1       Rule-based classification only
    2       Machine Learning classification only
    =====   ========================================

translate (``array``)
    If your content is in a language other than english, you can enter a translation parameter to run it through either
    a third-party translation provider, or use eContext's built in Machine Translation services.  More information about
    the translation services can be found in the `eContext API Translation`_ documentation.

    Each translation parameter should include two items.

    provider:
        The translation provider being used

    credentials:
        A credential parameter.  The type of this value depends on provider being used.

    .. code-block:: php
        :caption: Example:

        [
            "provider"=>"microsoft",
            "credential"=>[
                "client_id"=>"CLIENT_ID_FROM_AZURE",
                "client_secret"=>"CLIENT_SECRET_FROM_AZURE"
            ]
        ]

.. _eContext API Object Flags: http://econtext-api.readthedocs.io/en/stable/objects.html#object-flags
.. _eContext API Translation: http://econtext-api.readthedocs.io/en/stable/translation.html