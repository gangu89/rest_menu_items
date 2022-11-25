<?php

declare(strict_types = 1);

namespace Drupal\hello_ajax\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;

/**
 * Provides the 'hello Ajax' Block.
 *
 * @Block(
 *   id = "hello_ajax_block",
 *   admin_label = @Translation("Hello AJAX Block"),
 *   category= @Translation("Hello AJAX"),
 * )
 *
 * @package Drupal\hello_ajax\Plugin\Block
 */
class HelloAjaxBlock extends BlockBase implements BlockPluginInterface {

  /**
   * {@inheritDoc}
   */
  public function build(): array {
    return [
      '#markup' => $this->t("Hello AJAX!"),
      '#attached' => [
        'library' => [
          'hello_ajax/hello-ajax',
        ],
      ],
    ];
  }

}
