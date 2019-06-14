<?php

namespace Drupal\careful_raccoon\Seeder;

use Drupal\node\NodeInterface;
use Drupal\node\Entity\Node;

class BoeClusterSeeder {
  
  /**
   * Get the BOE Cluster.
   * 
   * @param array $data
   * @return NodeInterface|NULL
   */
  private static function get(array $data) : ?NodeInterface {
    $nids = \Drupal::entityQuery('node')
      ->condition('type', 'BOE_cluster')
      ->condition('field_boe_cluster', $data['cluster'])
      ->execute();      
    
    return empty($nids) ? NULL : Node::load(array_shift($nids));
  }
  
  /**
   * Get the administrative cluster or seed it if it does not exist.
   *
   * @param array $data
   * @return NodeInterface
   */
  public static function getOrSeed(array $data) : NodeInterface {
    if (!$node = self::get($data)) {
      $node = self::seed($data);
    }
    
    return $node;
  }
  
  /**
   * Seed the BOE Cluster.
   * 
   * @param array $data
   * @return NodeInterface
   */
  public static function seed(array $data) : NodeInterface {
    $node = Node::create([
        'type' => 'boe_cluster',
        'uid' => 1,
        'field_boe_cluster' => $data['cluster'],
        'field_representative' => $data['representative'],
    ]);
    
    $node->save();
    
    return $node;
  }
}
