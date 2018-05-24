<?php

namespace Drupal\icecream_waffle_orderform;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Connection;

class IcecreamManager {
  protected $connection;

  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  public function addOrder(string $keuze, string $smaak, string $toppings, int $bestelId) {
    $this->connection->insert('icecream_waffle_orderform')
      ->fields([
        'keuze' => $keuze,
        'smaak' => $smaak,
        'toppings' => $toppings,
        'bestellingId' => $bestelId,
      ])->execute()
    ;
  }
}