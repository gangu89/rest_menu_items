<?php

namespace Drupal\panels_ipe\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\panels\Plugin\DisplayVariant\PanelsDisplayVariant;

/**
 * Defines an interface for IPE Access plugins.
 */
interface IPEAccessInterface extends PluginInspectionInterface {

  /**
   * Provides logic to determine if a given plugin applies to a display.
   *
   * @param \Drupal\panels\Plugin\DisplayVariant\PanelsDisplayVariant $display
   *
   * @return bool
   */
  public function applies(PanelsDisplayVariant $display);

  /**
   * @param \Drupal\panels\Plugin\DisplayVariant\PanelsDisplayVariant $display
   *
   * @return mixed
   */
  public function access(PanelsDisplayVariant $display);

}
