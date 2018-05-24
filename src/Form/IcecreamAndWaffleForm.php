<?php


namespace Drupal\icecream_waffle_orderform\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\icecream_waffle_orderform\IcecreamManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class IcecreamAndWaffleForm extends ConfigFormBase {

  protected $icecreamManager;

  public function __construct(IcecreamManager $icecreamManager) {
    $this->icecreamManager = $icecreamManager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('icecream.icecream_manager')
    );
  }

  public function getFormId() {
    return 'icecream_waffle_orderform';
  }

  public function buildForm(array $form, formStateInterface $form_state) {

    $form['keuze'] = [
      '#type' => 'radios',
      '#options' => [
        'wafel' => 'wafel',
        'ijsje' => 'ijsje',
      ],
      '#attributes' => array(
        'required' => 'required',
      ),
      '#button_type' => 'primary',
    ];

    $form['smaak'] = [
      '#type' => 'select',
      '#options' => [
        'aardbei' => 'Aardbei',
        'vanille' => 'Vanille',
        'pistache' => 'Pistache',
        'banaan' => 'Banaan',
      ],
      '#states' => [
        'visible' => [
          ':input[name="keuze"]' => [
            'value' => 'ijsje',
          ]
        ],
      ],
    ];

    $form['toppings'] = [
      '#type' => 'checkboxes',
      '#options' => [
        'chocolade_saus' => 'Chocolade saus',
        'slagroom' => 'Slagroom',
        'stroop' => 'Stroop',
      ],
      '#states' => [
        'visible' => [
          ':input[name="keuze"]' => [
            'value' => 'wafel',
          ]
        ],
      ],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Opslaan',
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $toppings = $form_state->getValue('toppings');
    $toppingsString ='';
    foreach ($toppings as $topping){
      if(!empty($topping)){
        $toppingsString .= $topping . ', ';
      }
    }
    $config = $this->config('icecream.settings');
    if ($form_state->getValue('keuze')=='wafel'){
      if ($config->get('orderWaffles') == NULL){
        $this->config('icecream.settings')
          ->set('orderWaffles', 1)
          ->save();
      }
      $this->icecreamManager->addOrder($form_state->getValue('keuze'), $form_state->getValue('smaak'), $toppingsString, $config->get('orderWaffles'));
      $this->config('icecream.settings')
        ->set('countWaffles', ($config->get('countWaffles')+1))
        ->save();
      drupal_set_message('Uw keuze is succesvol opgeslagen. Er zijn ' . $config->get('countWaffles') . ' wafels en ' . $config->get('countIcecream') . ' ijsjes besteld');
      if ($config->get('countWaffles') >= $config->get('thresholdWaffles')){
        drupal_set_message('Treshold van wafels is bereikt.');
        $this->config('icecream.settings')
          ->set('countWaffles', 0)
          ->set('orderWaffles', ($config->get('orderWaffles')+1))
          ->save();
      } else {

        drupal_set_message('Threshold van wafels is nog niet bereikt.');
      }
    } else {
      if ($config->get('orderIcecream') == NULL){
        $this->config('icecream.settings')
          ->set('orderIcecream', 1)
          ->save();
      }
      $this->icecreamManager->addOrder($form_state->getValue('keuze'), $form_state->getValue('smaak'), $toppingsString, $config->get('orderIcecream'));
      $this->config('icecream.settings')
        ->set('countIcecream', ($config->get('countIcecream')+1))
        ->save();
      drupal_set_message('Uw keuze is succesvol opgeslagen. Er zijn ' . $config->get('countWaffles') . ' wafels en ' . $config->get('countIcecream') . ' ijsjes besteld');
      if ($config->get('countIcecream') >= $config->get('thresholdIcecream')){
        drupal_set_message('Treshold van ijsjes is bereikt.');
        $this->config('icecream.settings')
          ->set('countIcecream', 0)
          ->set('orderIcecream', ($config->get('orderIcecream')+1))
          ->save();
      } else {

        drupal_set_message('Threshold van ijsjes is nog niet bereikt.');
      }
    }

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