<?php

namespace Drupal\careful_raccoon\Commands;

use Drush\Commands\DrushCommands;
use Drupal\careful_raccoon\Seeder\SchoolSeeder;
use Drupal\careful_raccoon\Seeder\AdministrativeBuildingSeeder;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;

class CarefulRaccoonCommands extends DrushCommands {

  /**
   * Create schools
   * 
   * @param string $api
   *   The current api endpoint where we should get our data from.
   * 
   * @command careful-raccoon:seed
   * @usage careful-raccoon:seed
   *   Seed
   */
  public function seed($api = NULL) {
    $api = $api ?: 'https://api.hocoschools.org';
    
    $this->seedSchools($api);
    $this->seedAdministrativeBuildings();
    $this->seedFrontpage();
  }
  
  /**
   * Create the frontpage.
   */
  private function seedFrontpage() {
    $content = '
      <p>This site is a private API used by the Howard County Public School 
      System. For site issues, please contact the site administrators.</p>
    ';
    
    $page = Node::create([
      'type' => 'page',
      'title' => 'HCPSS Content API',
      'body' => [
        'value' => $content,
        'format' => 'basic_html',
      ],
    ]);
    
    $page->save();
    
    $url = Url::fromRoute('entity.node.canonical', [
        'node' => $page->id(),
    ])->toString();
    
    \Drupal::service('config.factory')
      ->getEditable('system.site')
      ->set('page.front', $url)
      ->save();
  }
  
  
  /**
   * Seed the schools.
   * 
   * @param string $endpoint
   */
  private function seedSchools(string $endpoint) {
    $payload  = file_get_contents("{$endpoint}/schools.json");
    $data     = json_decode($payload, TRUE);
    
    foreach ($data['schools'] as $acronyms) {
      foreach ($acronyms as $acronym) {
        $school_payload = file_get_contents("{$endpoint}/schools/$acronym.json");
        $school_data    = json_decode($school_payload, TRUE);
        
        SchoolSeeder::seed($school_data);
      }
    }
  }
    
  /**
   * Create administrative buildings.
   */
  private function seedAdministrativeBuildings() {
    AdministrativeBuildingSeeder::seed(
      'Central Office', 
      '10910 Clarksville Pike', 
      'Ellicott City', 
      '21043'
    );
    
    AdministrativeBuildingSeeder::seed(
      'Ascend One Center',
      '8930 Stanford Blvd',
      'Columbia',
      '21045'
    );
    
    AdministrativeBuildingSeeder::seed(
      'Old Cedar Lane School',
      '5451 Beaverkill Road',
      'Columbia',
      '21044'
    );
    
    AdministrativeBuildingSeeder::seed(
      'Mendenhall Building',
      '9020 Mendenhall Court',
      'Columbia',
      '21045'
    );
    
    AdministrativeBuildingSeeder::seed(
      'Ridge Road',
      '8800 Ridge Road',
      'Ellicott City',
      '21043'
    );
    
    AdministrativeBuildingSeeder::seed(
      'Dorsey Building',
      '9250 Bendix Road',
      'Columbia',
      '21045'
    );
    
    AdministrativeBuildingSeeder::seed(
      'Judy Center',
      '6700 Cradlerock Way',
      'Columbia',
      '21045'
    );
    
    AdministrativeBuildingSeeder::seed(
      'Warehouse',
      '6675 Amberton Drive',
      'Elkridge',
      '21075'
    );
  }
}
