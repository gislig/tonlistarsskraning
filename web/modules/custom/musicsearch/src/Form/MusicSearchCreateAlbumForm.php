<?php
namespace Drupal\musicsearch\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\musicsearch\Controller\MusicSearchController;
use Drupal\musicsearch\MusicSearchSalutation;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MusicSearchCreateAlbumForm extends FormBase {
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
    $form['album_name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Enter album name'),
      //'#autocomplete_route_name' => 'musicsearch.autocomplete_album',

    );

    $form['artist_name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Enter artist name'),
      '#autocomplete_route_name' => 'musicsearch.autocomplete_artist',
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => 'Submit',
    );
    return $form;
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {
    $album_name = $form_state->getValue('album_name');
    $artist_name = $form_state->getValue('artist_name');
    $album_exists = FALSE;
    $artist_exist = FALSE;

    // Here we check if the data exists in database
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery()->condition("type", "record")->execute();
    $records = $entity->loadMultiple($query);

    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery()->condition("type", "artist")->execute();
    $artists = $entity->loadMultiple($query);

    foreach($records as $album){
      //echo "<PRE>";
      if($album->label() == $album_name){
        $album_exists = TRUE;
      }
    }

    foreach($artists as $artist){
      //echo "<PRE>";
      if($artist->label() == $artist_name){
        $artist_exist = TRUE;
      }
    }

    $params['album_exists'] = $album_exists;
    $params['artist_exists'] = $artist_exist;
    $params['album_name'] = $album_name;
    $params['artist_name'] = $artist_name;
    $params['albums'] = $records;
    $params['artists'] = $artists;

    $tempstore = $this->tempStoreFactory->get('ex_form_values');
    $tempstore->set('params', $params);
    //die(print_r($album_exist->title));

    //$entity_ids = $query->execute();
    // Here we check if the data exists

    // If data exist go here
    $form_state->setRedirect('musicsearch.current_albums');
    // If data does not exist create the album
  }

}
