CONTENTS OF THIS FILE
---------------------

* Introduction
* Requirements
* Installation
* Configuration
* Maintainers


INTRODUCTION
------------

The "Create fields programatically" module saves you time by letting you create
fields from custom YAML configuration files.

This module is meant for development purpose and should not be installed on a 
server that faces the internet.

* For a full description of the module visit
https://www.drupal.org/project/field_create

* To submit bug reports and feature suggestions, or to track changes visit
https://www.drupal.org/project/issues/field_create


REQUIREMENTS
------------

This module does not require any additional modules outside of Drupal core.


INSTALLATION
------------

Install this module as you would normally install a contributed Drupal module.
Visit https://www.drupal.org/node/1897420 for more information.


CONFIGURATION
-------------

Enable "Create fields programmatically", either via "Extend" (/admin/modules) or via drush:
```bash
drush en field_create -y
```

Declare your fields definitions:

1. Option 1: use the provided hook <code>>hook_field_create_definitions()</code> in a custom module 
to declare your fields, as follow: 
```php
/**
 * Implements hook_field_create_definitions().
 */
function mymodule_field_create_definitions_alter(&$definitions) {
  $definitions['node']['myfield'] = [] // Detail your field.
}
```

2. Option 2: create your YAML config files based on the example provided in the module and
upload them directly from within the admin UI. Visit the admin page at 
Configuration > Development > Create Fields Programmatically (admin/config/development/field-create)

3. Option 3: Enable `field_create_from_json` module and convert JSON to YAML in the admin UI.

Now, you can run the Drush command:
```bash
drush field-create
```

Finally, uninstall the module. All your custom configurations will be automatically
deleted (see `field_create_uninstall()` hook).

```bash
drush pmu field_create -y
```


MAINTAINERS
-----------

* Matthieu Scarset (matthieuscarset) - https://www.drupal.org/u/matthieuscarset
