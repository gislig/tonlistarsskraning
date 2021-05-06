<?php

namespace Drupal\musicsearch\Controller;
use Drupal\Core\Controller\ControllerBase;
/**
 * Controller for the salutation message.
 */
class MusicSearchController extends ControllerBase {
  /**
   * Music Search.
   *
   * @return array
   *   Our message.
   */
  public function musicSearch() {
    return [
      '#markup' => $this->t('This is the music search'),
    ];
  }
}
