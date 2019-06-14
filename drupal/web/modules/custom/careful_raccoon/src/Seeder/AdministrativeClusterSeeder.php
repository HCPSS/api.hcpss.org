<?php

namespace Drupal\careful_raccoon\Seeder;

use Drupal\node\NodeInterface;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;

class AdministrativeClusterSeeder {
  
  /**
   * Get the administrative cluster.
   * 
   * @param array $data
   * @return NodeInterface|NULL
   */
  private static function get(array $data) : ?NodeInterface {
    $nids = \Drupal::entityQuery('node')
      ->condition('type', 'administrative_cluster')
      ->condition('field_cluster', $data['cluster'])
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
   * Seed the Administrative Cluster.
   * 
   * @param array $data
   * @return NodeInterface
   */
  public static function seed(array $data) : NodeInterface {
    $super = Paragraph::create([
        'type'        => 'person',
        'field_name'  => $data['community_superintendent']['name'],
        'field_phone' => $data['community_superintendent']['phone'],
        'field_email' => $data['community_superintendent']['email'],
    ]);
    $super->save();
    
    $pec = Paragraph::create([
        'type'        => 'person',
        'field_name'  => $data['pec_officer']['name'],
        'field_phone' => $data['pec_officer']['phone'],
        'field_email' => $data['pec_officer']['email'],
    ]);
    $pec->save();
    
    $node = Node::create([
        'type' => 'administrative_cluster',
        'uid' => 1,
        'title' => 'Administrative Cluster ' . $data['cluster'],
        'field_cluster' => $data['cluster'],
        'field_community_superintendent' => $super,
        'field_pec_officer' => $pec,
    ]);
    
    $node->save();
    
    return $node;
  }
}
