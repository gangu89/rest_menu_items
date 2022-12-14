<?php

namespace Drupal\Tests\panels\Unit;

use Prophecy\PhpUnit\ProphecyTrait;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\Context\ContextHandlerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\panels\Plugin\DisplayBuilder\StandardDisplayBuilder;
use Drupal\panels\Plugin\DisplayVariant\PanelsDisplayVariant;
use Drupal\Tests\UnitTestCase;
use Prophecy\Argument;

/**
 * @coversDefaultClass \Drupal\panels\Plugin\DisplayBuilder\StandardDisplayBuilder
 * @group Panels
 */
class StandardDisplayBuilderTest extends UnitTestCase {

  use ProphecyTrait;
  /**
   * @var \Drupal\panels\Plugin\DisplayBuilder\StandardDisplayBuilder
   */
  protected $builder;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    $context_handler = $this->prophesize(ContextHandlerInterface::class)
      ->reveal();
    $account = $this->prophesize(AccountInterface::class)
      ->reveal();
    $module_handler = $this->prophesize(ModuleHandlerInterface::class)
      ->reveal();
    $this->builder = new StandardDisplayBuilder([], 'standard', [], $context_handler, $account, $module_handler);
  }

  /**
   * @covers ::build
   */
  public function testBuild() {
    $regions = [];

    $block = $this->prophesize(BlockPluginInterface::class);
    $block->access(Argument::type(AccountInterface::class))
      ->willReturn(TRUE);
    $block->getConfiguration()->willReturn([]);
    $block->getPluginId()->willReturn('foo');
    $block->getBaseId()->willReturn('foo');
    $block->getDerivativeId()->willReturn('foo');
    $block->build()->willReturn(['#markup' => 'Foo!']);
    $regions['content']['foo'] = $block->reveal();

    $block = $this->prophesize(BlockPluginInterface::class);
    $block->access(Argument::type(AccountInterface::class))
      ->willReturn(TRUE);
    $block->getConfiguration()->willReturn([]);
    $block->getPluginId()->willReturn('bar');
    $block->getBaseId()->willReturn('bar');
    $block->getDerivativeId()->willReturn('bar');
    $block->build()->willReturn(['#markup' => 'Bar...']);
    $regions['sidebar']['bar'] = $block->reveal();

    $block = $this->prophesize(BlockPluginInterface::class);
    $block->access(Argument::type(AccountInterface::class))
      ->willReturn(FALSE);
    $regions['sidebar']['baz'] = $block->reveal();

    $regions['footer'] = [];

    $panels_display = $this->prophesize(PanelsDisplayVariant::class);
    $panels_display->getRenderedPageTitle()->willReturn('test');
    $panels_display->getRegionAssignments()->willReturn($regions);
    $panels_display->getContexts()->willReturn([]);
    $panels_display->getLayout()->willReturn(NULL);

    $build = $this->builder->build($panels_display->reveal());
    // Ensure that regions get the proper prefix and suffix.
    $this->assertEquals('<div class="block-region-content">', $build['content']['#prefix']);
    $this->assertEquals('</div>', $build['content']['#suffix']);

    // Ensure that blocks which allowed access showed up...
    $this->assertEquals('Foo!', $build['content']['foo']['content']['#markup']);
    $this->assertEquals('Bar...', $build['sidebar']['bar']['content']['#markup']);
    // ...and that blocks which disallowed access did not.
    $this->assertArrayNotHasKey('baz', $build['sidebar']);
    // Ensure that empty regions don't show up in $build.
    $this->assertArrayNotHasKey('footer', $build);
  }

}
