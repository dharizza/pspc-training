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
    return strrev($text);
  }

  /**
   * Uppercase all the text received. Example: text to TEXT.
   */
  public function uppercase($text): string {
    return strtoupper($text);
  }

  /**
   * Lowercase all the text received. TEXT to text.
   */
  public function lowercase($text): string {
    return strtolower($text);
  }

  /**
   * Title case all the text received. 'Example Text' to 'Example text'.
   */
  public function titleCase($text): string {
    return ucfirst($text);
  }

}
