<?php
namespace Drupal\musicsearch\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\musicsearch\Controller\MusicSearchController;
use Drupal\musicsearch\Controller\MusicSearchAutocompleteController;
use Drupal\musicsearch\MusicSearchSalutation;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;

class MusicSearchForm extends FormBase {
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
    return 'musicsearch_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Enter spotify name'),
      '#description' => $this->t('Please provide the spotify name'),
      '#autocomplete_route_name' => 'musicsearch.autocomplete',
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => 'Submit',
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $id = $form_state->getValue('name');
    $result = $this->salutation->searchSpotifyByItem($id);

    //die($result);
    $tempstore = $this->tempStoreFactory->get('ex_form_values');
    $tempstore->set('params', $result);

    $form_state->setRedirect('musicsearch.list_form');
    //die($result);
    //return $result;
    //$this->submitForm($form, $form_state);
    // Kalla á service-inn

  }
}
