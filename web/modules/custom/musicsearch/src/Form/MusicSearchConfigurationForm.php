<?php
  namespace Drupal\musicsearch\Form;
  use Drupal\Core\Form\ConfigFormBase;
  use Drupal\Core\Form\FormStateInterface;

  class MusicSearchConfigurationForm extends ConfigFormBase {
    protected function getEditableConfigNames() {
      return ['musicsearch.custom_salutation'];
    }

    public function getFormId() {
      // TODO: Implement getFormId() method.
      return 'salutation_configuration_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
      $config = $this->config('musicsearch.custom_salutation');

      $form['musicsearch_spotifyconfig_tokenurl'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Connection String'),
        '#description' => $this->t('Please provide the connection string for Spotify Token'),
        '#default_value' => $config->get('musicsearch_spotifyconfig_tokenurl'),
      ];

      $form['musicsearch_spotifyconfig_clientid'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Spotify Client ID'),
        '#description' => $this->t('Please provide the client id for spotify'),
        '#default_value' => $config->get('musicsearch_spotifyconfig_clientid'),
      ];

      $form['musicsearch_spotifyconfig_clientsecret'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Spotify Client Secret'),
        '#description' => $this->t('Please provide the client secret for spotify'),
        '#default_value' => $config->get('musicsearch_spotifyconfig_clientsecret'),
      ];

      return parent::buildForm($form, $form_state);
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
      $this->config('musicsearch.custom_salutation')
        ->set('musicsearch_spotifyconfig_tokenurl', $form_state->getValue('musicsearch_spotifyconfig_tokenurl'))
        ->set('musicsearch_spotifyconfig_clientid', $form_state->getValue('musicsearch_spotifyconfig_clientid'))
        ->set('musicsearch_spotifyconfig_clientsecret', $form_state->getValue('musicsearch_spotifyconfig_clientsecret'))
        ->save();
      parent::submitForm($form, $form_state);
    }

  }
