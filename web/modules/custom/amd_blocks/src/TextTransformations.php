<?php

declare(strict_types=1);

namespace Drupal\amd_blocks;

/**
 * Service that provides multiple text transformations.
 */
final class TextTransformations {

  /**
   * Reverse the text received. Example: text to txet.
   */
  public function reverse($text): string {
    \Drupal::logger('amd_channel')->warning('The text was reversed.');
    return strrev($text);
  }

  /**
   * Uppercase all the text received. Example: text to TEXT.
   */
  public function uppercase($text): string {
    \Drupal::logger('amd_channel')->warning('The text was transformed to be uppercase.');
    return strtoupper($text);
  }

  /**
   * Lowercase all the text received. TEXT to text.
   */
  public function lowercase($text): string {
    \Drupal::logger('amd_channel')->warning('The text was transformed to be lowercase.');
    return strtolower($text);
  }

  /**
   * Title case all the text received. 'Example Text' to 'Example text'.
   */
  public function titleCase($text): string {
    \Drupal::logger('amd_channel')->warning('The text was transformed to be titlecase.');
    return ucfirst($text);
  }

}
