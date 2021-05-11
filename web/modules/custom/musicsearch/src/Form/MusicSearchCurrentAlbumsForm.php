<?php
namespace Drupal\musicsearch\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\musicsearch\Controller\MusicSearchController;
use Drupal\musicsearch\MusicSearchSalutation;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MusicSearchCurrentAlbumsForm extends FormBase {
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

    $album_exists = $params['album_exists'];
    $artist_exist = $params['artist_exists'];
    $album_name = $params['album_name'];
    $artist_name = $params['artist_name'];
    $records = $params['albums'];
    $artists = $params['artists'];

    /*
    echo "<h3>Testing</h3>";
    echo $album_name;
    echo $album_exists;
    echo $artist_name;
    echo $artist_exist;
    echo $records;
    echo $artists;
    */
    $array = [];
    foreach($records as $record){
      if(strpos($album_name, $record->label()) !== false){
        array_push($array, $record->label());
      }
    }

    if($album_exists == TRUE){
      $form['markup1'] = array(
        '#type' => 'markup',
        '#markup' => '<h2>The following albums where found in the database - NEEDS TO BE IMPLEMENTED</h2><br>',
      );

      foreach ($array as $record) {
        $form[$record] = [
          '#type' => 'radio',
          '#title' => $record,
          '#name' => $record,
        ];
      }

      $form['markup2'] = array(
        '#type' => 'markup',
        '#markup' => '<h3>If the album you are creating is in this list, please select that album from the list, it will then update all fields in the database with new data.</h3><br>',
      );
    }else{
      $form['markup2'] = array(
        '#type' => 'markup',
        '#markup' => '<h3>No albums where found in the database</h3><br>',
      );
    }
    $form['markup3'] = array(
      '#type' => 'markup',
      '#markup' => '<h2>Album that you are creating</h2><br><div>You are creating <b>'. $album_name .'</b> with the following artist <b>'. $artist_name .'</b></div>',
    );


    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => 'Create',
    );
    return $form;
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {
    $tempstore = $this->tempStoreFactory->get('ex_form_values');
    $params = $tempstore->get('params');

    $album_name = $params['album_name'];
    $artist_name = $params['artist_name'];
    $album_data = "";
    /*
    $album_exists = $params['album_exists'];
    $artist_exist = $params['artist_exists'];

    $records = $params['albums'];
    $artists = $params['artists'];
    */

    $artists = $this->salutation->searchSpotifyByArtistOrTrack($artist_name, "artist");
    $artists_json = json_decode($artists);

    foreach($artists_json->artists->items as $artist){
      if($artist->name == $artist_name){
        $albums = $this->salutation->searchSpotifyAlbumsByArtist($artist->id);
        $album_json = json_decode($albums);
        foreach($album_json->items as $album){
          if($album->name == $album_name){
            $album_data = $album;
            $params['artist_id'] = $artist->id;
          }
        }
      }
    }

    $params['album_data'] = $album_data;

    $node = \Drupal::entityTypeManager()->getStorage('node')->create(['type' => 'record', 'title' => $album_name]);
    $node->save();
    $tempstore->set('params', $params);

    // Here we check if the data exists
    // show new form with existing albums

    $form_state->setRedirect('musicsearch.list_form');

  }

}
