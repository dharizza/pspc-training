<?php

declare(strict_types=1);

namespace Drupal\download_files\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Provides a Download Files form.
 */
final class DownloadFilesForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'download_files_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['media'] = [
      '#type' => 'select',
      '#title' => $this->t('Select a file to download'),
      '#options' => $this->getFilesOptions(),
    ];

    $form['pass_phrase'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#description' => $this->t('Enter your email address to retrieve the file.'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Download'),
      ],
    ];

    return $form;
  }

  public function getFilesOptions() {
    // Get list of files using database abstraction layer.
    // $results = \Drupal::database()
    //   ->select('file_managed', 'f')
    //   ->fields('f', ['filename', 'uri'])
    //   ->orderBy('f.filename', 'ASC')
    //   ->execute()
    //   ->fetchAll();
    
    // $options = [];
    // foreach ($results as $file) {
    //   $options[$file->uri] = $file->filename;
    // }

    // Get list of files using Entity Query.
    $results = \Drupal::entityQuery('file')
      ->condition('status', 1)
      ->accessCheck()
      ->execute();

    $files = File::loadMultiple($results);

    $options = [];
    foreach ($files as $file) {
      // $options[$file->uri->value] = $file->filename->value;
      $options[$file->getFileUri()] = $file->getFilename();
    }
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    parent::validateForm($form, $form_state);
    $pass = $form_state->getValue('pass_phrase');

    if (!strpos($pass, '@evolvingweb.com')) {
      $form_state->setErrorByName('pass_phrase', $this->t('Incorrect email! Try again.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $uri = $form_state->getValue('media');
    $response = new BinaryFileResponse($uri);
    $response->setContentDisposition('attachment');
    $form_state->setResponse($response);
  }

}
