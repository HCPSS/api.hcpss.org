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
      'field_facility' => FacilitySeeder::seed([
        'address'       => [
          'street'      => $street,
          'city'        => $city,
          'postal_code' => $postal_code,
        ],
        'environment' => ['water' => [
          'source'           => 'city',
          'extended_testing' => FALSE,
        ]],
      ]),
    ]);
            
    $node->save();
    
    return $node;
  }
}
