<?php
namespace App\services;

use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Resources\Preference\Item;

class MercadoPagoService
{
  public function __construct()
  {
    MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN'));
  }

  public function generatePreference($cart)
  {
    $client = new PreferenceClient();
    $items = [];
    foreach ($cart as $cartItem) {
      $item = new Item();
      $item->title = $cartItem->producto->nombre;
      $item->quantity = $cartItem->cantidad;
      $item->unit_price = $cartItem->producto->precio;
      $item->picture_url = $cartItem->producto->imagen;

      $items[] = $item;
    }

    $preference = $client->create([
      "external_reference" => "teste",
      "items" => $items,
      'back_urls' => [
        'success' => env('BACK_URL_SUCCESS'),
        'failure' => env('BACK_URL_ERROR'),
      ]
    ]);

    return $preference;
  }


}

?>