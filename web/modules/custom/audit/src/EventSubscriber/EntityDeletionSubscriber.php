<?php

declare(strict_types=1);

namespace Drupal\audit\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\core_event_dispatcher\EntityHookEvents;
use Drupal\core_event_dispatcher\Event\Entity\EntityDeleteEvent;

/**
 * @todo Add description for this subscriber.
 */
final class EntityDeletionSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      EntityHookEvents::ENTITY_DELETE => ['logDeletion'],
    ];
  }

  /**
   * If the entity delete event is triggered, log record.
   */
  public function logDeletion(EntityDeleteEvent $event) {
    // Add logic.
    $deleted_entity = $event->getEntity();
    ksm($deleted_entity);

    $data = [
      'label' => $deleted_entity->label(),
      'deleted' => time(),
      'deleted_by' => \Drupal::currentUser()->id(),
      'entity_type' => $deleted_entity->getEntityTypeId(),
      'entity_bundle' => $deleted_entity->bundle(),
    ];

    $record = \Drupal::entityTypeManager()->getStorage('deletion_record')->create($data);
    $record->save();

    // 'created',
    // 'changed',
    // 'deleted_entity_author',
  }

}
