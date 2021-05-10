<?php
namespace Drupal\musicsearch\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\musicsearch\Controller\MusicSearchController;

class MusicSearchForm extends FormBase {


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
      '#autocomplete_route_name' => 'musicsearch.autocomplete',
    );
    /*
    $form['musicsearch_searchfield'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter spotify name'),
      '#description' => $this->t('Please provide the spotify name'),
      '#autocomplete_route_name' => 'musicsearch.autocomplete',
    ];
    */
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $my = new MusicSearchController();
    $id = $form_state->getValue('name');
    $result = $my.getMusic($id);

    //$this->submitForm($form, $form_state);
    // Kalla á service-inn
  }
}
