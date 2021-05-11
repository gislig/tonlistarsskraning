<?php

namespace Drupal\musicsearch\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Serialization\Json;
use Drupal\musicsearch\MusicSearchSalutation;


use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * Controller for the salutation message.
 */
class MusicSearchController extends ControllerBase {

  public function saveData(){

  }

  /**
   * Music Search.
   *
   * @return array
   *   Our message.
   */
  public function musicSearch() {
    $this->console_log("Hello world");
    return [
      //'#markup' => $this->t($search_results),
      //'#markup' => $this->salutation->getSalutation(),
      '#markup' => $this->salutation->searchSpotifyByItem("Muse"),
    ];
  }

  public function getMusic() {
    return $this->salutation->searchSpotifyByItem("Muse");
  }
}
