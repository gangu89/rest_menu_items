<?php

namespace Drupal\field_create\Commands;

use Drupal\field_create\FieldCreateManagerInterface;
use Drush\Commands\DrushCommands;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * Drush commands for field_create.
 */
class FieldCreateCommands extends DrushCommands {

  /**
   * The logger.channel.field_create service.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * The field_create service.
   *
   * @var \Drupal\field_createg\FieldCreateManagerInterface
   */
  protected $manager;

  /**
   * FieldCreateCommands constructor.
   *
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger.channel.field_create service.
   * @param \Drupal\field_createg\FieldCreateManagerInterface $manager
   *   The field_create manager service.
   */
  public function __construct(LoggerInterface $logger, FieldCreateManagerInterface $manager) {
    parent::__construct();

    $this->logger = $logger;
    $this->manager = $manager;
  }

  /**
   * Create fields from imported configuration files.
   *
   * @command field_create
   * @aliases fcreate
   * @option entity-type-id Filter field creation by a given Entity Type ID.
   */
  public function run(array $command_options = ['entity-type-id' => NULL]) {

    $definitions = $this->manager->getFieldsDefinitions($command_options['entity-type-id']);

    $progress_bar = new ProgressBar($this->output(), count($definitions));

    foreach ($definitions as $entity_type_id => $list) {
      $this->manager->createEntityFields($entity_type_id, $list);
      $progress_bar->advance();

      $this->logger->notice('Processed {count} field definitions.', [
        'count' => count($list),
      ]);
    }

    $progress_bar->finish();
  }

}
