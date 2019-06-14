<?php

namespace Drupal\careful_raccoon\Seeder;

use Drupal\node\NodeInterface;
use Drupal\taxonomy\TermInterface;
use Drupal\taxonomy\Entity\Term;

class AchievementSeeder {
  
  /**
   * Get the achievement.
   * 
   * @param array $data
   * @return NodeInterface|NULL
   */
  private static function get(array $data) : ?TermInterface {
    $tids = \Drupal::entityQuery('taxonomy_term')
      ->condition('vid', 'achievements')
      ->condition('name', $data['name'])
      ->execute();
    
    return empty($tids) ? NULL : Term::load(array_shift($tids));
  }
  
  /**
   * Get or seed achievement.
   * 
   * @param array $data
   * @return TermInterface
   */
  public static function getOrSeed(array $data) : TermInterface {
    if ($award = self::get($data)) {
      if (!empty($data['years']) && !$award->field_by_year->value) {
        $award->field_by_year = TRUE;
        $award->save();
      }
    } else {
      $award = self::seed($data);
    }
    
    return $award;
  }
  
  /**
   * Seed the term.
   * 
   * @param array $data
   * @return TermInterface
   */
  public static function seed(array $data) : TermInterface {
    $award = Term::create([
        'vid' => 'achievements',
        'name' => $data['name'],
        'field_machine_name' => $data['machine_name'],
    ]);
    
    if (!empty($data['color'])) {
      $award->field_icon_color = ['color' => $data['color']];
    }
    
    if (!empty($data['url'])) {
      $award->field_url->uri = $data['url'];
    }
    
    $award->save();
    
    return $award;
  }
}
