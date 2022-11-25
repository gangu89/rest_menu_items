<?php

/**
 * @file
 * Contains hook examples for this module.
 */

/**
 * Implements hook_field_create_definitions().
 */
function hook_field_create_definitions(array &$definitions) {
  // Define your fields here by entity type.
  $definitions['node'] = [
    'demo_field' => [
      'name'    => 'demo_field',
      'label'   => 'This is my field',
      'type'    => 'string',
      'force'   => FALSE, // No update once field exists.
      'bundles' => [
        'page' => [
          'label'    => 'Oulala',
          'displays' => [
            'form' => [
              'default' => [
                'region' => 'content', // Force display.
              ],
            ],
            'view' => [
              'default' => [
                'label' => 'above',
              ],
              'teaser'  => [
                'label' => 'hidden',
              ],
            ],
          ],
        ],
      ],
    ],
  ];
}
