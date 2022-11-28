Migrate Queue Importer Module.

Provides the ability to create cron migrations(configuration entities) with a
reference towards migration entities in order to import them during CRON runs.
You can also define additional options such as update and ignore dependencies
for each of the referenced migrations.

## Requirements

* Migrate Tools module, which also creates a dependency towards Migrate Plus
and Core Migrate module.

## Configuration

Module ships with a simple configuration UI which allows you to create, edit
and delete cron migrations. Navigate to
/admin/config/migrate_queue_importer/cron_migration to use the UI.

## Maintainers
 - Dumitru Postovan (@postovan-dumitru) drupal.org/u/postovan-dumitru

## Supporting organizations:
Axis Communications AB - The company is the sponsor for the module.
