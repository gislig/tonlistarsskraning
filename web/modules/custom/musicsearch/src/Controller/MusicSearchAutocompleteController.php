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
  public function autocomplete(request $request){
    $this->console_log("autocomplete");
    //$results = [];
    $input = $request->query->get('q');
    $this->console_log($input);
    //if (!$input) {
    //  return new JsonResponse($results);
    //}
    //$input = Xss::filter($input);
    /*
    $res = $this->salutation->searchSpotifyByItem("Muse");
    //$res = $this->salutation->searchSpotifyByItem($input);
    $decoded = json_decode($res);

    foreach($decoded as $code){
      $results[] = [
        'value' => $code["album"],
        'label' => $code["name"],
      ];
    }
    return new JsonResponse($results);
    */
    return "";
  }
}
