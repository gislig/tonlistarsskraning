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
  /**
   * The salutation service.
   *
   * @var \Drupal\musicsearch\MusicSearchSalutation
   */
  protected $salutation;

  /**
   * MusicSearchController constructor.
   *
   * @param \Drupal\musicsearch\MusicSearchSalutation $salutation
   *   The salutation service.
   */
  public function __construct(MusicSearchSalutation $salutation){
    $this->salutation = $salutation;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container){
    return new static(
      $container->get('musicsearch.salutation')
    );
  }

  public function saveData(){

  }

  function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
      ');';
    if ($with_script_tags) {
      $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
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
      //'#markup' => MusicSearchForm\
    ];
  }

  public function getMusic() {
    return $this->salutation->searchSpotifyByItem("Muse");
  }
}
