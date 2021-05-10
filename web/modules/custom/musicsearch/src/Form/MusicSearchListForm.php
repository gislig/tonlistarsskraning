<?php
namespace Drupal\musicsearch\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\musicsearch\Controller\MusicSearchController;

class MusicSearchListForm extends FormBase {
  public function getFormId() {
    // TODO: Implement getFormId() method.
    return 'musicsearch_list_form';
  }

  /**
   * {@inheritdoc}
   */

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['musicsearch_list_searchfield'] = [
    ];
    return $form;
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
