<?php
namespace Drupal\musicsearch\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\musicsearch\Controller\MusicSearchController;
use Drupal\musicsearch\Controller\MusicSearchAutocompleteController;

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

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => 'Submit',
    );
    /*
    $form['musicsearch_searchfield'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter spotify name'),
      '#description' => $this->t('Please provide the spotify name'),
    ];
    */
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }
  function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
      ');';
    if ($with_script_tags) {
      $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $my = new MusicSearchController;
    $id = $form_state->getValue('name');
    $result = $my->getMusic($id);
    $this->console_log($result);
    return "";
    //$this->submitForm($form, $form_state);
    // Kalla á service-inn
  }
}
