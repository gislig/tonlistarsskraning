<?php

namespace Drupal\musicsearch\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Serialization\Json;
/**
 * Controller for the salutation message.
 */
class MusicSearchController extends ControllerBase {
  public function authSpotify(){
    /* START AUTHENTICATION SPOTIFY */
    $connectionString = "https://accounts.spotify.com/api/token";
    $SPOTIFY_API_CLIENT_ID = "b7aae92e30ad44d494a6ea47f5eac226";
    $SPOTIFY_API_CLIENT_SECRET = "964aec03d8484b30875c37d39e153f11";

    $key = base64_encode($SPOTIFY_API_CLIENT_ID . ':' . $SPOTIFY_API_CLIENT_SECRET);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $connectionString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_POST, 1);

    $headers = array();
    $headers[] = "Authorization: Basic " . $key;
    $headers[] = "Content-Type: application/x-www-form-urlencoded";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    curl_close($ch);
    /* END Authentication */

    return $result;
  }

  public function searchSpotify($uri){
    $my_token = $this->authSpotify();

    $token = json_decode($my_token);
    $options = array(
      'method' => 'GET',
      'timeout' => 3,
      'headers' => array(
        'Accept' => 'application/json',
        'Authorization' => "Bearer " . $token->access_token,
      ),
    );

    $response = \Drupal::httpClient()->get($uri, $options);
    $search_results = (string) $response->getBody();

    return $search_results;
  }
  /**
   * Music Search.
   *
   * @return array
   *   Our message.
   */
  public function musicSearch() {
    /* Discogs */
    /**/

    $uri = "https://api.spotify.com/v1/artists/1vCWHaC5f2uS3yhpwWbIA6";
    $search_results = $this->searchSpotify($uri);

    return [
      '#markup' => $this->t($search_results),
    ];
  }
}
