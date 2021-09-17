<?php

class PlacesPlugin extends Omeka_Plugin_AbstractPlugin
{

  protected $_hooks = array(
    'define_routes',
  );

  /**
   * People define_routes hook
   * Defines public-only routes for browse all stories and view a single oral history.
   */
  public function hookDefineRoutes($args)
  {
    $router = $args['router'];

    $router->addRoute('placesDefault',
      new Zend_Controller_Router_Route('places',
        array(
          'module'     => 'places',
          'controller' => 'index',
          'action'     => 'browse',
        )
      )
    );

    $router->addRoute('placesShow',
      new Zend_Controller_Router_Route('places/:permalink',
        array(
          'module'     => 'places',
          'controller' => 'items',
          'action'     => 'show'
        ),
        array(
          'permalink' => '[a-zA-z0-9][a-zA-Z0-9\-]*'
        )
      )
    );
  }
}
