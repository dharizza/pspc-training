<?php

declare(strict_types=1);

namespace Drupal\audit\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\audit\Event\IncidentReport;
use Drupal\audit\Event\IncidentReportEvents;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher;

/**
 * Provides a Audit form.
 */
final class IncidentReportForm extends FormBase {

  protected EntityTypeManager $entityTypeManager;

  protected ContainerAwareEventDispatcher $eventDispatcher;

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('event_dispatcher'),
    );
  }

  public function __construct(EntityTypeManager $entity_type_manager, ContainerAwareEventDispatcher $event_dispatcher) {
    $this->entityTypeManager = $entity_type_manager;
    $this->eventDispatcher = $event_dispatcher;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'audit_incident_report';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['reporter_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Reporter name'),
      '#required' => TRUE,
      '#description' => $this->t('Type your name here.'),
    ];

    $form['reporter_email'] = [
      '#type' => 'email',
      '#title' => $this->t('Reporter email'),
      '#required' => TRUE,
      '#description' => $this->t('Type your email address here.'),
    ];

    $form['entity'] = [
      '#type' => 'select',
      '#title' => $this->t('Select the entity that was deleted incorrectly'),
      '#required' => TRUE,
      '#options' => $this->getEntities(),
    ];

    $form['report'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Send'),
      ],
    ];

    return $form;
  }

  public function getEntities() {
    $storage = $this->entityTypeManager->getStorage('deletion_record');
    $query = $storage->getQuery();
    $query->accessCheck(true);
    $query->sort('deleted', 'DESC');
    $ids = $query->execute();

    $records = $storage->loadMultiple($ids);

    $options = [];
    foreach ($records as $key => $item) {
      $options[$key] = $item->label->value;
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    if (mb_strlen($form_state->getValue('report')) < 16) {
      $form_state->setErrorByName(
        'report',
        $this->t('Message should be at least 15 characters.'),
      );
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    // Get the values from the form_state.
    $reporterName = $form_state->getValue('reporter_name');
    $reporterEmail = $form_state->getValue('reporter_email');
    $entity = $form_state->getValue('entity');
    $report = $form_state->getValue('report');

    // Create instance of the IncidentReport event object.
    $eventObject = new IncidentReport($reporterName, $reporterEmail, $entity, $report);
    // $event_dispatcher = \Drupal::service('event_dispatcher');
    $this->eventDispatcher->dispatch($eventObject, IncidentReportEvents::NEW_INCIDENT);

    $this->messenger()->addStatus($this->t('The message has been sent.'));
    $form_state->setRedirect('<front>');
  }

}
