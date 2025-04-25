<?php

declare(strict_types=1);

namespace Drupal\audit\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\core_event_dispatcher\EntityHookEvents;
use Drupal\core_event_dispatcher\Event\Entity\EntityDeleteEvent;
use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\audit\Event\IncidentReportEvents;
use Drupal\audit\Event\IncidentReport;
use Drupal\Core\Session\AccountProxy;

/**
 * @todo Add description for this subscriber.
 */
final class EntityDeletionSubscriber implements EventSubscriberInterface {
  /**
   * Stores the current_user service.
   * 
   * @var Drupal\Core\Session\AccountProxy;
   */
  protected AccountProxy $currentUser;

  public function __construct(AccountProxy $currentUser) {
    $this->currentUser = $currentUser;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      EntityHookEvents::ENTITY_DELETE => ['logDeletion'],
      IncidentReportEvents::NEW_INCIDENT => ['logIncident'],
    ];
  }

  /**
   * If the entity delete event is triggered, log record.
   */
  public function logDeletion(EntityDeleteEvent $event) {
    // Add logic.
    $deleted_entity = $event->getEntity();
    $entity_type = $deleted_entity->getEntityTypeId();

    // Do nothing for config entities.
    if ($deleted_entity instanceof ConfigEntityInterface) {
      return ;
    }

    // Do nothing for path_aliases.
    if ($entity_type == 'path_alias') {
      return ;
    }

    // In all other cases create a DeletionRecord entity.
    $data = [
      'label' => $deleted_entity->label(),
      'deleted' => time(),
      'deleted_by' => $this->currentUser->id(),
      'entity_type' => $entity_type,
      'entity_bundle' => $deleted_entity->bundle(),
    ];

    if (isset($deleted_entity->created)) {
      $data['created'] = $deleted_entity->created;
    }

    if (isset($deleted_entity->changed)) {
      $data['changed'] = $deleted_entity->changed;
    }

    if (isset($deleted_entity->uid)) {
      $data['deleted_entity_author'] = $deleted_entity->uid;
    }

    $record = \Drupal::entityTypeManager()->getStorage('deletion_record')->create($data);
    $record->save();
  }

  /**
   * If the new incident event is triggered, log it.
   */
  public function logIncident(IncidentReport $event) {
    $name = $event->getReporterName();
    $email = $event->getReporterEmail();
    $entity = $event->getDeletedEntity();
    $report = $event->getReport();

    \Drupal::logger('audit')->alert("New incident reported by " . $name . " (" . $email . ") on entity " . $entity . ". Details: " . $report);
    $event->stopPropagation();
  }

}
