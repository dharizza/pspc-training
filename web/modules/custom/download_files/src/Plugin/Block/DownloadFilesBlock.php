<?php

declare(strict_types=1);

namespace Drupal\download_files\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a download files block block.
 */
#[Block(
  id: "download_files_block",
  admin_label: new TranslatableMarkup("Download files block"),
  category: new TranslatableMarkup("Custom"),
)]
final class DownloadFilesBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $build['content'] = [
      '#markup' => $this->t('It works!'),
    ];
    return $build;
  }

}
