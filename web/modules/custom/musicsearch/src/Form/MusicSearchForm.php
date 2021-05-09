<?php
  namespace Drupal\musicsearch\Form;
  use Drupal\Core\Form\FormStateInterface;

  class TestElementFormClass {

    public function buildForm(array $form, FormStateInterface $formState) {
      $form['description'] = [
        '#type' => 'item',
        '#markup' => $this->t('Example of single text'),
      ];

      return $form;
    }
  }
