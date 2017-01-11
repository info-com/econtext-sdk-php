.. _result-result:

eContext\\Result
================

The base-class that most eContext interactions should use to process result sets.  A result stores pages of result
documents retrieved from the eContext API and allows a user to page through them, abstracting away the need to process
in batches.  This works by saving eContext API interactions into temporary files on disk, and then paging through in
sequence when you're ready to see the results.  All of this is handled in the background, so you only need to focus on
reading the results.

One of the consequences of this are that there are certain elements inside the ``Result`` object which change with each
page load.  For example, many calls populate a ``categories`` attribute that allows you to lookup a category given an
id.  Each time a new eContext API page is loaded, that dictionary will change, which means that there is no guarantee
that the same category id will exist.  Please be aware of this, and if you need category, be sure to export that out of
the result object in the scope that you require.

.. toctree::
    :caption: Methods
    :maxdepth: 1
    :glob:

    getbody
    getcurrentpage
    geterrorcode
    geterrormessage
    haserror
    yieldpages
