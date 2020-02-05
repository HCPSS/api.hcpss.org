<?php

namespace Drupal\careful_raccoon\Seeder;

use Drupal\node\NodeInterface;
use Drupal\node\Entity\Node;

class AdministrativeBuildingSeeder {

  /**
   * Seed the Administrative Building.
   *
   * @param string $name
   * @param string $street
   * @param string $city
   * @param string $postal_code
   * @return NodeInterface
   */
  public static function seed(string $name, string $street, string $city, string $postal_code) : NodeInterface {
    $node = Node::create([
      'type' => 'administrative_building',
      'uid' => 1,
      'title' => $name,
      'field_address' => [
        'address_line1'       => $street,
        'locality'            => $city,
        'postal_code'         => $postal_code,
        'administrative_area' => 'MD',
        'country_code'        => 'US',
      ],
    ]);

    $node->save();

    return $node;
  }
}
