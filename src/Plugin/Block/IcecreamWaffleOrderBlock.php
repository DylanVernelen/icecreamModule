<?php
namespace Drupal\icecream_waffle_orderform\Plugin\Block;
use Drupal\Core\Block\BlockBase;
/**
 * Defines a icecream or waffle order form block.
 *
 * @Block(
 *  id = "icecream_waffle_orderform_block",
 *  admin_label = @Translation("orderform"),
 * )
 */
class IcecreamWaffleOrderBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    return \Drupal::formBuilder()->getForm('Drupal\icecream_waffle_orderform\Form\IcecreamAndWaffleForm');
  }
}