# context node edited since

Drupal context plugin condition for checking time since last edit(use) of content type.

## Description

This module is a plugin for the context module. It serves as a condition you can use to set a context within you installation. The condition triggers on node edit and will check the lastest edit date by the current user for the current content type and compare it with this plugin's setting. For this the plugin uses the *current user* and *current content type* and check for the most recent edit date, than compares it with te time frame set up with this plugin. When the latest edit date is further back in time than the selected time, the condition will be *TRUE*

## Installation instructions

1. Install the module, and enable it.
2. Go to the Context admin *admin/structure/context* and select 'Node edited since given date condition' under conditions.
3. Select the time since the last edit and save the context
4. Good job the condition is now active

## TODO:

- Find appropriate name for plugin (Node date (and ask for edit/view option))
- refactor code
- make documentation
- also trigger on node view (let user use default 'node' condition to choose edit of view mode.)
