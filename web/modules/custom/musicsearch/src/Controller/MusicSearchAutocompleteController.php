<?php

namespace Drupal\musicsearch\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Serialization\Json;
use Drupal\musicsearch\MusicSearchSalutation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Entity\Element\EntityAutocomplete;

use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * Controller for the salutation message.
 */
class MusicSearchAutocompleteController {
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

  // https://codimth.com/blog/web/drupal/custom-autocomplete-text-fields-using-drupal-8-9
  public function autocompleteAlbum(request $request){
    $results = [];
    $input = $request->query->get('q');
    if (!$input) {
      return new JsonResponse($results);
    }

    $input = Xss::filter($input);

    $res = $this->salutation->searchSpotifyByArtistOrTrack($input,"track");
    $decoded = json_decode($res);

    foreach($decoded->artists->items as $artist){
      $results[] = [
        'value' => $artist->id,
        'label' => $artist->name,
      ];
    }

    return new JsonResponse($results);
  }

  public function autocompleteArtist(Request $request){
    $results = [];
    $input = $request->query->get('q');
    if (strlen($input) < 3) {
      return new JsonResponse($results);
    }

    $uri = "https://api.spotify.com/v1/search?q=artist:" . $input . "&type=artist";
    $res = $this->salutation->searchSpotify($uri);

    //$res = $this->salutation->searchSpotifyByArtistOrTrack($input,"artist");
    $decoded = json_decode($res);

    foreach($decoded->{artists}->{items} as $artist){
      $results[] = [
        'value' => $artist->{name},
        'label' => $artist->{name} . " - spotify",
      ];
    }

    return new JsonResponse($results);
  }
}
