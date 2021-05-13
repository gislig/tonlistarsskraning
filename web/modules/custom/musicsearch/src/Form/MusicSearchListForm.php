<?php
namespace Drupal\musicsearch\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\musicsearch\Controller\MusicSearchController;
use Drupal\musicsearch\MusicSearchSalutation;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MusicSearchListForm extends FormBase {
  /**
   * Drupal\Core\TempStore\PrivateTempStoreFactory definition.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  private $tempStoreFactory;

  /**
   * The salutation service.
   *
   * @var \Drupal\musicsearch\MusicSearchSalutation
   */
  protected $salutation;

  /**
   * MusicSearchForm constructor.
   *
   * @param \Drupal\musicsearch\MusicSearchSalutation $salutation
   *   The salutation service.
   */
  public function __construct(MusicSearchSalutation $salutation, PrivateTempStoreFactory $tempStoreFactory){
    $this->salutation = $salutation;
    $this->tempStoreFactory = $tempStoreFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container){
    return new static(
      $container->get('musicsearch.salutation'),
      $container->get('tempstore.private')
    );
  }

  public function getFormId() {
    // TODO: Implement getFormId() method.
    return 'musicsearch_list_form';
  }

  /**
   * {@inheritdoc}
   */

  public function buildForm(array $form, FormStateInterface $form_state) {
    $tempstore = $this->tempStoreFactory->get('ex_form_values');
    $params = $tempstore->get('params');
    $album_name = $params['album_name'];
    $artist_name = $params['artist_name'];
    $album_data = $params['album_data'];
    $artist_id = $params['artist_id'];
    $image_640 = "";
    $image_300 = "";
    $image_64 = "";

    // If there is no album data
    if($album_data == ""){
      return $this->redirect('musicsearch.create_album');
    }


    foreach($album_data->images as $img){
      if($img->width == 640){
        $image_640 = $img->url;
      }
      if($img->width == 300){
        $image_300 = $img->url;
      }
      if($img->width == 64){
        $image_64 = $img->url;
      }
    }

    //echo "<pre>";
    //die(print_r($album_data));



    $tracks = $this->salutation->searchSpotifyAlbumTracks($album_data->id);
    $tracks_json = json_decode($tracks);


    $artist_data = $this->salutation->searchSpotifyArtistData($artist_id);


    $form['spotify'] = array(
      '#type' => 'table',
      '#caption' => $this
        ->t('Data from Spotify'),
      '#header' => array(
        $this
          ->t('Image'),
        $this
          ->t('Name'),
        $this
          ->t('Artist'),
        $this
          ->t('Type'),
        $this
          ->t('Spotify iD'),
        $this
          ->t('Copy to db'),
      ),
    );

    foreach($tracks_json->items as $track){
      $form['spotify'][$track->id]['image'] = array(
        '#type' => 'markup',
        '#prefix' => '<div id="box" class="image_class" align="center">',
        '#suffix' => '</div>',
        '#markup' => '<img src="'. $image_64 .'" alt="picture">',
      );

      $form['spotify'][$track->id]['name'] = array(
        '#type' => 'label',
        '#title' => $this
          ->t($track->name),
      );

      $form['spotify'][$track->id]['artist'] = array(
        '#type' => 'label',
        '#title' => $this
          ->t($artist_name),
      );

      $form['spotify'][$track->id]['type'] = array(
        '#type' => 'label',
        '#title' => $this
          ->t("type here"),
      );

      $form['spotify'][$track->id]['spotify_id'] = array(
        '#type' => 'label',
        '#title' => $this
          ->t($track->id),
      );

      $form['spotify'][$track->id]['add'] = array(
        '#type' => 'checkbox',
        '#states' => [
          'input[name="album"]' => ['checked' => FALSE],
        ],
      );
    }

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => 'Submit',
    );

    return $form;
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {

    $tempstore = $this->tempStoreFactory->get('ex_form_values');
    $params = $tempstore->get('params');
    $album_name = $params['album_name'];
    $artist_name = $params['artist_name'];
    $album_data = $params['album_data'];
    $artist_id = $params['artist_id'];

    $track_name = "";
    $track_length_sec = "";
    $track_type = "";
    // Go through selection

    // spotify[1Q5kgpp4pmyGqPwNBzkSrw][add]
    echo "<pre>";
    //foreach()
    //die(print_r($form_state->getValues()));
    //die(print_r($form_state->getValue("spotify")));
    foreach($form_state->getValue("spotify") as $key => $spotify) {
      // Here we search for track
      if($spotify["add"] == 1){
        $track_details = $this->salutation->searchSpotifyTrackById($key);
        $track_details = json_decode($track_details);

        //die(print_r($track_details->name));
        $track_name = $track_details->name;
        //die(print_r($track_name));
        $track_length_sec = $track_details->duration_ms / 1000;
        $track_type = "pop";

        //die(print_r($track_name));
        $node = \Drupal::entityTypeManager()->getStorage('node')->create(['type' => 'song']);
        $node->set('title',$track_name);
        $node->set('field_duration_in_seconds',$track_length_sec);
        $node->set('field_song_type',$track_type);
        $node->set('field_spotify_id',$key);
        $node->save();
      }
    }

    // Here we update the album with the image

    //die(print_r($form["spotify"]));
    /*foreach ($form_state->getValues() as $key => $value) {
      echo $key . ': ' . $value;
    }
    */
    //die(print_r("[" . $form['spotify[1Q5kgpp4pmyGqPwNBzkSrw][add]'] . " and " . $form['spotify[6KVM6U9Wcxgjh0jDjoRDgh][add]'] . "] \n"));

    // Save the artist
    // Go through each album and save it
    //
    $form_state->setRedirect('musicsearch.create_album');
  }

}
