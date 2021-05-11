<?php

namespace Drupal\musicsearch\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Serialization\Json;
use Drupal\musicsearch\MusicSearchSalutation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * Controller for the salutation message.
 */
class MusicSearchAutocompleteController extends ControllerBase {
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


  function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
      ');';
    if ($with_script_tags) {
      $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
  }

  public function autocompleteAlbum(request $request){
    $results = [];
    $input = $request->query->get('q');

    $res = $this->salutation->searchSpotifyByArtistOrTrack($input,"artist");
    $decoded = json_decode($res);
    foreach($decoded->artists->items as $artist){
      $results[] = [
        'value' => $artist->name,
        'label' => $artist->name,
      ];
    }

    return new JsonResponse($results);
  }

  public function autocompleteArtist(request $request){
    $results = [];
    $input = $request->query->get('q');

    $res = $this->salutation->searchSpotifyByArtistOrTrack($input,"track");
    $decoded = json_decode($res);
    foreach($decoded->tracks->items as $track){
      $results[] = [
        'value' => $track->name,
        'label' => $track->name,
      ];
    }

    return new JsonResponse($results);
  }
}
