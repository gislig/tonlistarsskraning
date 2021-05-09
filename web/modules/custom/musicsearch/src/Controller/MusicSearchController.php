<?php

namespace Drupal\musicsearch\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Serialization\Json;
use Drupal\musicsearch\MusicSearchSalutation;
use Drupal\musicsearch\MusicSearchForm;

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
  protected $searchForm;

  /**
   * MusicSearchController constructor.
   *
   * @param \Drupal\musicsearch\MusicSearchSalutation $salutation
   *   The salutation service.
   */
  public function __construct(MusicSearchSalutation $salutation){
    $this->salutation = $salutation;
    //$this->searchForm = $searchForm;
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
  /**
   * Music Search.
   *
   * @return array
   *   Our message.
   */
  public function musicSearch() {

    return [
      //'#markup' => $this->t($search_results),
      //'#markup' => $this->salutation->getSalutation(),
      '#markup' => $this->salutation->getSalutation(),
    ];
  }
}
