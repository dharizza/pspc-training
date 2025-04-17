<?php

declare(strict_types=1);

namespace Drupal\amd_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an amd hello world block block.
 *
 * @Block(
 *   id = "amd_blocks_hello_world",
 *   admin_label = @Translation("AMD Hello World Block"),
 *   category = @Translation("Custom"),
 * )
 */
final class HelloWorldBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $build['content'] = [
      '#markup' => $this->t('It works!'),
    ];
    return $build;
  }

}
