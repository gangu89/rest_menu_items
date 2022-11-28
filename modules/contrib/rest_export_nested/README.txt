CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Requirements
 * Installation
 * Configuration
 * Maintainers


INTRODUCTION
------------

This module extends core's REST Export views display to automatically convert
any JSON string field to JSON in the output. It works with Views Field View.

Theoretically it should work with any field which displays a JSON string without
 any surrounding HTML.

REQUIREMENTS
------------

This module requires:
- drupal:rest
- drupal:views


INSTALLATION
------------

 * Install as you would normally install a contributed Drupal module. Visit
   https://www.drupal.org/docs/8/extending-drupal-8/installing-drupal-8-modules
   for further information.


CONFIGURATION
-------------

1. Install module.
2. In your view, add a display of type "REST export nested"
3. Configure view.
When adding a Views Field View field, make sure the output of the field is set
to "REST export" or "REST export nested".

Example using Views Field View:

1. Install and enable Views Field View
2. Create view display of referenced content (e.g. Articles) of type
"REST export" or "REST export nested"
3. Add a relationship to the host entity and contextual filter of host entity ID
4. Create a view display "REST export nested" of parent entity type
5. Add required fields (e.g. "nid", "title")
6. Add a field of type "Views field", configure with the correct View and
display and pass "nid" as the contextual filter
7. Using another source of JSON

If your JSON is stored as field data or you're generating it another way, you
may need to adjust the row style options. In your "REST export nested" display,
edit your fields settings and select "Raw output" for the JSON field.

MAINTAINERS
-----------

Current maintainers:
 * Ian McLean (imclean) - https://www.drupal.org/u/imclean
 * Debora Antunes (dgaspara) - https://www.drupal.org/u/dgaspara
