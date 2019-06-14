<?php

namespace Drupal\careful_raccoon\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Drupal\careful_raccoon\Normalizer\LegacySchoolNormalizer;

class LegacyController extends ControllerBase {
  
  /**
   * Legacy display for all schools.
   * 
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function schools(Request $request) {
    $data = [
      "school_bus_locator" => "https://www.infofinderi.com/ifi/?cid=HCP2IOASIJVW",
      "online_payments" => "https://osp.osmsinc.com/HowardMD/BVModules/CategoryTemplates/Detailed%20List%20with%20Properties/Category.aspx?categoryid=DA011",
    ];
    
    $nids = \Drupal::entityQuery('node')
      ->condition('type', 'school')
      ->condition('status', 1)
      ->execute();
    
    $schools = Node::loadMultiple($nids);
    
    foreach ($schools as $school) {
      $data['schools'][$school->field_level->value][] = $school->field_acronym->value;
    }
    
    return new JsonResponse($data);
  }
  
  /**
   * Display a school in the legacy api format.
   * 
   * @param string $slug
   * @param Request $request
   * @throws NotFoundHttpException
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function school($slug, Request $request) {
    $acronym = explode('.', $slug)[0];
        
    $nids = \Drupal::entityQuery('node')
      ->condition('field_acronym', $acronym)
      ->condition('status', 1)
      ->execute();
    
    if (empty($nids)) {
      throw new NotFoundHttpException("No $acronym school.");
    }
    
    $node = Node::load(array_shift($nids));
    $normalizer = new LegacySchoolNormalizer();
    
    return new JsonResponse($normalizer->normalize($node));
  }
}
