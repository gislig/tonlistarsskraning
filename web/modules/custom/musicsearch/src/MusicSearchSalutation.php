<?php

namespace Drupal\musicsearch;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Prepares the salutation to the music search.
 */
class MusicSearchSalutation {

  use StringTranslationTrait;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * MusicSearchSalutation constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
      ');';
    if ($with_script_tags) {
      $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
  }

  public function authSpotify(){
    /* START AUTHENTICATION SPOTIFY */

    $config = $this->configFactory->get('musicsearch.custom_salutation');
    $connectionString = $config->get('musicsearch_spotifyconfig_tokenurl');
    $SPOTIFY_API_CLIENT_ID = $config->get('musicsearch_spotifyconfig_clientid');
    $SPOTIFY_API_CLIENT_SECRET = $config->get('musicsearch_spotifyconfig_clientsecret');

    /* TODO : Try to find solution to this
    if($connectionString === NULL) {
      return $this->redirectToRoute('homepage');
    }
    */

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



  public function searchSpotifyByItem($q){
    $type = "track";
    // curl -X "GET" "https://api.spotify.com/v1/search?q=Muse&type=track%2Cartist&market=US&limit=10&offset=5"
    // type : track, artist
    // q: muse
    $this->console_log("finding things");
    $path = "https://api.spotify.com/v1/search?q=" . $q . "&type=" . $type . "&limit=50";
    return $this->searchSpotify($path);
  }

  /**
   * Returns the salutation.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   The salutation message.
   */
  public function getSalutation($uri) {
    // "https://api.spotify.com/v1/artists/1vCWHaC5f2uS3yhpwWbIA6"
    $search_results = $this->searchSpotify($uri);

    // send to new form
    return $search_results;
  }

  public function searchSpotifyByArtistOrTrack($value, $TrackOrArtist){
    $path = "https://api.spotify.com/v1/search?q=" . $value . "&type=" . $TrackOrArtist . "&limit=50";
    return $this->searchSpotify($path);
  }

  public function searchSpotifyAlbumsByArtist($artist_id){
    $path = "https://api.spotify.com/v1/artists/" . $artist_id . "/albums";
    return $this->searchSpotify($path);
  }

  public function searchSpotifyArtistData($artist_id){
    $path = "https://api.spotify.com/v1/artists/" . $artist_id;
    return $this->searchSpotify($path);
  }

  public function searchSpotifyAlbumTracks($album_id){
    $path = "https://api.spotify.com/v1/albums/" . $album_id . "/tracks";
    return $this->searchSpotify($path);
  }

  public function searchSpotifyTrackById($track_id){
    $path = "https://api.spotify.com/v1/tracks/" . $track_id;
    return $this->searchSpotify($path);
  }
}
