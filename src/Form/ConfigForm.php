<?php

namespace Drupal\pco_api\Form;

use Drupal\Core\Form\FormStateInterface;

class ConfigForm extends ConfigFormBase {

  public function getFormId() {
    return 'pco_api_settings_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->getConfig();

    $form['token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('PCO API Token'),
      '#default_value' => $config->get('token'),
      '#description' => $this->t('Your Planning Center API token.'),
    ];

    $form['base_uri'] = [
      '#type' => 'textfield',
      '#title' => $this->t('PCO API Base URL'),
      '#default_value' => $config->get('base_uri'),
      '#description' => $this->t('Include trailing slash.'),
    ];

    $form['secret'] = [
      '#type' => 'key_select',
      '#title' => $this->t('PCO API Secret'),
      '#default_value' => $config->get('secret'),
      '#description' => $this->t('Your Planning Center API secret.')
    ];

    return parent::buildForm($form, $form_state);
  }

}
