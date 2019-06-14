<?php

namespace Drupal\careful_raccoon\Normalizer;

use Drupal\node\NodeInterface;
use Drupal\paragraphs\Entity\Paragraph;

class LegacySchoolNormalizer {

  /**
   * Perform the normalization.
   * 
   * @param NodeInterface $node
   * @return array
   */
  public function normalize(NodeInterface $node) : array {
    $point           = $node->field_facility->entity->field_geo_location->value;
    list($lon, $lat) = $this->getLonLatFromPoint($point);
    $facility        = $node->field_facility->entity;
    $admin_cluster   = $node->field_administrative_cluster->entity;
    $comm_super      = $admin_cluster->field_community_superintendent->entity;
    $pec_officer     = $admin_cluster->field_pec_officer->entity;
    $board_cluster   = $node->field_board_cluster->entity;
    
    $data = [
      'acronym'       => $node->field_acronym->value,
      'name'          => $this->getShortName($node->getTitle()),
      'full_name'     => $node->getTitle(),
      'primary_color' => $this->hexToRgb($node->field_primary_color->color),
      'level'         => $node->field_level->value,
      'contact' => [
        'phone' => $node->field_phone->value,
        'fax'   => $node->field_fax->value,
      ],
      'address' => [
        'street'      => $facility->field_address->address_line1,
        'city'        => $facility->field_address->locality,
        'postal_code' => $facility->field_address->postal_code,
        'latitude'    => $lat,
        'longitude'   => $lon,
      ],
      'principal' => $node->field_principal->value,
      'hours' => [
        'open'  => $this->formatSeconds($node->field_hours->from),
        'close' => $this->formatSeconds($node->field_hours->to),
      ],
      'environment' => [
        'water' => [
          'source'           => $facility->field_water_source->value,
          'extended_testing' => (bool)$facility->field_extended_water_testing->value,
        ],
      ],
      'mascot'      => $node->field_mascot->value,
      'profile'     => $node->field_school_profile->uri,
      'msde_report' => $node->field_msde_report->uri,
      'walk_area'   => $node->field_walk_area_map->uri,
      'title_1'     => (bool)$node->field_title_1->value,
      'administrative_cluster' => [
        'cluster' => (int)$admin_cluster->field_cluster->value,
        'community_superintendent' => [
          'name'  => $comm_super->field_name->value,
          'phone' => $comm_super->field_phone->value,
          'email' => $comm_super->field_email->value,
        ],
        'pec_officer' => [
          'name'  => $pec_officer->field_name->value,
          'phone' => $pec_officer->field_phone->value,
          'email' => $pec_officer->field_email->value,
        ],
      ],
      'boe_cluster' => [
        'cluster'        => $board_cluster->field_boe_cluster->value,
        'representative' => $board_cluster->field_representative->value,
      ],
    ];
    
    foreach ($node->field_achievements as $achievement_field) {      
      $data['achievements'][] = $this->awardToData($achievement_field->entity);
    }
    
    $data['common'] = [
      "school_bus_locator" => "https://www.infofinderi.com/ifi/?cid=HCP2IOASIJVW",
      "online_payments"    => "https://osp.osmsinc.com/HowardMD/BVModules/CategoryTemplates/Detailed%20List%20with%20Properties/Category.aspx?categoryid=DA011",
    ];

    return $data;
  }
  
  /**
   * Normalize award.
   * 
   * @param Paragraph $award
   * @return array
   */
  private function awardToData(Paragraph $award) : array {
    /** @var \Drupal\taxonomy\Entity\Term $achievement */
    $achievement = $award->field_achievement->entity;
    
    list($name, $level) = $this->extractAwardLevel($achievement->getName());
    
    $data = [
        'machine_name' => $achievement->field_machine_name->value,
        'name' => $name,
    ];
    
    if ($level) {
      $data['level'] = $level;
    }
    
    if ($uri = $achievement->field_url->uri) {
      $data['url'] = $uri;
    }
    
    if ($color = $achievement->field_icon_color->color) {
      $data['color'] = strtoupper(trim($color, '#'));
    }
    
    foreach ($award->field_years as $year_field) {
      $data['years'][] = $year_field->value;
    }
    
    return $data;
  }
  
  /**
   * Extract the award level from the name of an award.
   * 
   * @param string $name
   * @return array
   */
  protected function extractAwardLevel(string $name) : array {
    $matches = [];    
    preg_match('/(.+) (Bronze|Silver|Gold)/', $name, $matches);
    
    return [
      $matches[1] ?: $name,
      array_key_exists(2, $matches) ? $matches[2] : NULL,
    ];
  }
  
  /**
   * Get the short name from the longname.
   * 
   * @param string $full_name
   * @return string
   */
  protected function getShortName(string $full_name) : string {
    return str_replace([
      ' Elementary School', 
      ' Middle School', 
      ' High School',
    ], '', $full_name);
  }
  
  /**
   * Format seconds as a time.
   * 
   * @param int $seconds
   * @return string|NULL
   */
  protected function formatSeconds(int $seconds) : ?string {
    if ($seconds) {
      $minutes = floor($seconds / 60);
      $hours   = floor($minutes / 60);
      $minutes = $minutes - ($hours * 60);
      $ampm    = 'a.m.';
      
      if ($hours > 12) {
        $hours = $hours - 12;
        $ampm  = 'p.m.';
      }
      
      return vsprintf('%d:%02d %s', [$hours, $minutes, $ampm]);
    }
      
    return NULL;
  }
  
  /**
   * Take a point like this "POINT(-76.862600,39.186331)" and return an array
   * like this [-76.862600,39.186331].
   * 
   * @param string $point
   * @return array
   */
  protected function getLonLatFromPoint(string $point) : array {
    $matches = [];
    preg_match('/^POINT\((-*\d+\.\d+) (-*\d+\.\d+)\)$/', $point, $matches);
    
    return [$matches[1], $matches[2]];
  }
  
  /**
   * Convert hex color to rgb color.
   * 
   * @param string $hex
   * @return string
   */
  protected function hexToRgb(string $hex) : string {
    list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
    return "rgb($r,$g,$b)";
  }
}
