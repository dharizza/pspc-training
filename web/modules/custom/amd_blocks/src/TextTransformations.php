<?php

declare(strict_types=1);

namespace Drupal\amd_blocks;

use Drupal\Core\Logger\LoggerChannel;
use Drupal\Core\Logger\LoggerChannelFactory;

/**
 * Service that provides multiple text transformations.
 */
final class TextTransformations {

  /**
   * Logger channel.
   * 
   * @varDrupal\Core\Logger\LoggerChannel
   */
  protected LoggerChannel $logger;

  public function __construct(LoggerChannelFactory $loggerFactory) {
    $this->logger = $loggerFactory->get('amd_channel');
  }

  /**
   * Reverse the text received. Example: text to txet.
   */
  public function reverse($text): string {
    // \Drupal::logger('amd_channel')->warning('The text was reversed.');
    $this->logger->warning('The text was reversed.');
    return strrev($text);
  }

  /**
   * Uppercase all the text received. Example: text to TEXT.
   */
  public function uppercase($text): string {
    // \Drupal::logger('amd_channel')->warning('The text was transformed to be uppercase.');
    $this->logger->warning('The text was transformed to be uppercase.');
    return strtoupper($text);
  }

  /**
   * Lowercase all the text received. TEXT to text.
   */
  public function lowercase($text): string {
    // \Drupal::logger('amd_channel')->warning('The text was transformed to be lowercase.');
    $this->logger->warning('The text was transformed to be lowercase.');
    return strtolower($text);
  }

  /**
   * Title case all the text received. 'Example Text' to 'Example text'.
   */
  public function titleCase($text): string {
    // \Drupal::logger('amd_channel')->warning('The text was transformed to be titlecase.');
    $this->logger->warning('The text was transformed to be titlecase.');
    return ucfirst($text);
  }

}
