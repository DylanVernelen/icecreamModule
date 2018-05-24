<?php
namespace Drupal\icecream_waffle_orderform\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class IcecreamAndWaffleSettingsForm extends ConfigFormBase {

  public function getFormId(){
    return 'icecream_admin_settings';
  }

  public function buildForm(array $form, FormStateInterface $form_state){
    $config = $this->config('icecream.settings');

    $form['icecream'] = array(
      '#type' => 'number',
      '#title' => 'Set threshold of icecream',
      '#attributes' => array(
        'min' => 0,
        'value' => $config->get('thresholdIcecream'),
      ),
      '#required' => TRUE,
    );

    $form['waffle'] = array(
      '#type' => 'number',
      '#title' => 'Set threshold of waffles',
      '#attributes' => array(
        'min' => 0,
        'value' => $config->get('thresholdWaffles'),
      ),
      '#required' => TRUE,
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => 'Opslaan',
    );

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state){
    $this->config('icecream.settings')
      ->set('thresholdIcecream', $form_state->getValue('icecream'))
      ->set('thresholdWaffles', $form_state->getValue('waffle'))
      ->save();
    drupal_set_message('De thresholds zijn succesvol opgeslagen');
  }

  /**
   * Gets the configuration names that will be editable.
   *
   * @return array
   *   An array of configuration object names that are editable if called in
   *   conjunction with the trait's config() method.
   */
  protected function getEditableConfigNames() {
    return [
      'icecream.settings',
    ];
  }
}