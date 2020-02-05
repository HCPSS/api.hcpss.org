<?php

namespace Drupal\careful_raccoon\Seeder;

use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\node\NodeInterface;
use Drupal\careful_raccoon\Seeder\AdministrativeClusterSeeder as AdminClusterSeeder;
use Drupal\Core\Entity\EntityStorageBase;

class SchoolSeeder {

  /**
   * Create a school from the school data.
   *
   * @param array $school_data
   */
  public static function seed(array $school_data) : NodeInterface {
    $boe_cluster = BoeClusterSeeder::getOrSeed($school_data['boe_cluster']);
    $admin_cluster = AdminClusterSeeder::getOrSeed(
      $school_data['administrative_cluster']
    );

    $school = Node::create([
      'type'                => 'school',
      'uid'                 => 1,
      'title'               => $school_data['full_name'],
      'field_acronym'       => $school_data['acronym'],
      'field_primary_color' => [
        'color'   => self::convertRgbToHex($school_data['primary_color']),
        'opacity' => NULL,
      ],
      'field_hours' => [
        'from' => $school_data['hours']['open']  ? self::convertTimeToSeconds($school_data['hours']['open'])  : NULL,
        'to'   => $school_data['hours']['close'] ? self::convertTimeToSeconds($school_data['hours']['close']) : NULL,
      ],
      'field_level'                  => $school_data['level'],
      'field_mascot'                 => $school_data['mascot'],
      'field_school_profile'         => ['uri' => $school_data['profile']],
      'field_msde_report'            => ['uri' => $school_data['msde_report']],
      'field_phone'                  => $school_data['contact']['phone'],
      'field_fax'                    => $school_data['contact']['fax'],
      'field_principal'              => $school_data['principal'],
      'field_administrative_cluster' => $admin_cluster,
      'field_board_cluster'          => $boe_cluster,
      'field_facility'               => $facility,
      'field_title_1'                => $school_data['title_1'],
      'field_address' => [
        'address_line1'       => str_replace(' (Route 108)', '', $school_data['address']['street']),
        'locality'            => $school_data['address']['city'],
        'postal_code'         => $school_data['address']['postal_code'],
        'administrative_area' => 'MD',
        'country_code'        => 'US',
      ],
      'field_well_water' => $school_data['environment']['water']['source'] == 'well',
    ]);

    if (!empty($school_data['walk_area'])) {
      $school->field_walk_area_map = ['uri' => $school_data['walk_area']];
    }

    foreach ($school_data['achievements'] as $achievement) {
      if (!empty($achievement['level'])) {
        $achievement['name'] .= ' ' . $achievement['level'];
      }

      $achievement_term = AchievementSeeder::getOrSeed($achievement);

      $award = Paragraph::create([
        'type' => 'award',
        'field_achievement' => $achievement_term,
      ]);

      if (!empty($achievement['years'])) {
        foreach ($achievement['years'] as $year) {
          $award->field_years[] = $year;
        }
      }

      $award->save();

      $school->field_achievements[] = $award;
    }

    $school->save();

    return $school;
  }

  /**
   * Convert a time like this "9:25 a.m." to the number of seconds from
   * midnight lile this 33900.
   *
   * @param string $time
   * @return int
   */
  private static function convertTimeToSeconds(string $time) : int {
    $matches = [];
    preg_match('/^(\d+)\:(\d+)\s(a\.m\.|p\.m\.)$/', $time, $matches);

    $hours   = (int)$matches[1];
    $minutes = (int)$matches[2];
    $ampm    = $matches[3];

    if ($ampm == 'p.m.') {
      $hours += 12;
    }

    $minutes += $hours * 60;

    return $minutes * 60;
  }

  /**
   * Convert a string like this "rgb(0,128,255)" to one like this "#00adff".
   *
   * @param string $rgb
   * @return string
   */
  private static function convertRgbToHex(string $rgb) : string {
    list($red, $green, $blue) = explode(',', str_replace(['rgb(', ')'], '', $rgb));

    return vsprintf('#%02s%02s%02s', [
        dechex((int)$red),
        dechex((int)$green),
        dechex((int)$blue),
    ]);
  }
}
