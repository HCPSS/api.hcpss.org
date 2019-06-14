<?php

namespace Drupal\careful_raccoon\Seeder;

use Drupal\paragraphs\ParagraphInterface;
use Drupal\paragraphs\Entity\Paragraph;

class FacilitySeeder {
  
  /**
   * Create a facility
   *
   * @param array $data
   * @return ParagraphInterface
   */
  public static function seed(array $data) : ParagraphInterface {
    $facility = Paragraph::create([
        'type' => 'facility',
        'field_address' => [
            'address_line1' => str_replace(' (Route 108)', '', $data['address']['street']),
            'locality' => $data['address']['city'],
            'postal_code' => $data['address']['postal_code'],
            'administrative_area' => 'MD',
            'country_code' => 'US',
        ],
        'field_water_source' =>  $data['environment']['water']['source'],
        'field_extended_water_testing' =>  $data['environment']['water']['extended_testing'],
    ]);
    
    $facility->save();
    
    return $facility;
  }
}
