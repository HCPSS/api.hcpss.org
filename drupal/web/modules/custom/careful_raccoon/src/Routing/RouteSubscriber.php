<?php

namespace Drupal\careful_raccoon\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

class RouteSubscriber extends RouteSubscriberBase {
  
  /**
   * {@inheritDoc}
   * @see \Drupal\Core\Routing\RouteSubscriberBase::alterRoutes()
   */
  protected function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('view.administrative_clusters.page_1')) {
      $route->setOption('_admin_route', FALSE);
    }
    
    if ($route = $collection->get('view.administrative_buildings.page_1')) {
      $route->setOption('_admin_route', FALSE);
    }
  }
}
