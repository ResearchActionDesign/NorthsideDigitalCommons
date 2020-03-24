<?php

class PeoplePlugin extends Omeka_Plugin_AbstractPlugin
{

  protected $_hooks = array(
    'define_routes',
  );

  /**
   * People define_routes hook
   * Defines public-only routes for browse all people and view a single person page.
   */
  public function hookDefineRoutes($args)
  {
    $router = $args['router'];

    $router->addRoute('peopleDefault',
      new Zend_Controller_Router_Route('people',
        array(
          'module'     => 'people',
          'controller' => 'index',
          'action'     => 'browse',
        )
      )
    );

    $router->addRoute('peopleShow',
      new Zend_Controller_Router_Route('people/show/:permalink',
        array(
          'module'     => 'people',
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
