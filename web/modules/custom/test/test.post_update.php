<?php

/**
 * Populate the new "External ID" field from an array of known values.
 */
function test_post_update_populate_id_field(&$sandbox) {
  $nids_to_extids = [
    76432 => 'HhZLS',
    76428 => 'RKWrd',
    76425 => 'TJs22',
    76424 => 'wpVIR',
    76423 => 'AEyS9',
    76418 => 'tSVVJ',
    76415 => 'tF4NJ',
    76414 => 'MXmnc',
    76413 => 'aQLwW',
    76412 => 'NVRV0',
    76411 => 'gk55Q',
    76410 => 'Wj1DC',
    76406 => 'CuCjT',
    76405 => '4qYFe',
    76393 => 'cBPyY',
    76390 => 'MmWJl',
    76388 => 'K4QYQ',
    76387 => 'HUpeh',
    76386 => 'P3MN2',
    76385 => 'qALjM',
  ];

  $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple(array_keys($nids_to_extids));
  foreach ($nodes as $node) {
    $node->set('field_external_id', $nids_to_extids[$node->id()]);
    $node->save();
  }
}
