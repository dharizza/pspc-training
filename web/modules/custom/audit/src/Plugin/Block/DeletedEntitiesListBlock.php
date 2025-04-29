<?php

declare(strict_types=1);

namespace Drupal\audit\Plugin\Block;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * Provides a deleted entities list block block.
 */
#[Block(
  id: "audit_deleted_entities_list_block",
  admin_label: new TranslatableMarkup("Deleted Entities List Block"),
  category: new TranslatableMarkup("Custom"),
)]
final class DeletedEntitiesListBlock extends BlockBase implements ContainerFactoryPluginInterface {
  protected EntityTypeManager $entityTypeManager;

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
    );
  }

  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManager $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $storage = $this->entityTypeManager->getStorage('deletion_record');
    $query = $storage->getQuery();
    $query->accessCheck(true);
    $query->sort('deleted', 'DESC');
    $query->range(0, 3);
    $ids = $query->execute();

    $records = $storage->loadMultiple($ids);

    $output = '<h3>Recently deleted entities</h3><ol>';

    foreach ($records as $item) {
      $output = $output . '<li>' . $item->label->value . '</li>';
    }

    $output = $output . '</ol>';

    $build['content'] = [
      '#markup' => $output,
    ];
    return $build;
  }

  public function getCacheTags() {
    $tags = [
      'deletion_record_list',
    ];

    return Cache::mergeTags(parent::getCacheTags(), $tags);
  }

}
