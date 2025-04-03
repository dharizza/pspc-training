<?php

declare(strict_types=1);

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Hello World routes.
 */
final class HelloController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function hello($name = NULL): array {
    $output = $this->t('Hello World!');

    if ($name) {
      $output = $this->t('Hello @person!', ['@person' => $name]);
    }

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $output,
    ];

    return $build;
  }

}
