<?php

declare(strict_types=1);

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Link;
use Drupal\Core\Url;

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

  /**
   * Builds the response.
   */
  public function helloNameNode($name, $nid): array {
    $node = Node::load($nid);
    // Print variables.
    // ksm($node->getTitle());
    // ksm($node->title->value);
    // ksm($node->id());
    // ksm($node->nid->value);
    // ksm($node->bundle());
    // ksm($node->field_specialty->entity->name->value);
    // ksm($node);

    // If node exist let's print the title.
    if ($node) {
      // Build link manually using Url and Link classes.
      // $url = Url::fromRoute('entity.node.canonical', ['node' => $nid]);
      // $link = Link::fromTextAndUrl('node link', $url);

      // Alternative using the node function toLink()
      $link = $node->toLink();

      $output = $this->t('Hello @person! The title of the node is @title', [
        '@person' => $name,
        '@title' => $link->toString()
      ]);
    } else {
      $output = $this->t('Hello @person! The node with ID @id does not exist.', [
        '@person' => $name,
        '@id' => $nid
      ]);
    }

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $output,
    ];

    return $build;
  }

}
