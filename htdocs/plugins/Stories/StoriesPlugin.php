<?php

class StoriesPlugin extends Omeka_Plugin_AbstractPlugin
{

  protected $_hooks = array(
    'define_routes',
  );


  public static $ORAL_HISTORY_ITEM_TYPE = 4;

  /**
   * People define_routes hook
   * Defines public-only routes for browse all stories and view a single oral history.
   */
  public function hookDefineRoutes($args)
  {
    $router = $args['router'];

    $router->addRoute('storiesBrowse',
      new Zend_Controller_Router_Route('oral-histories',
        array(
          'module'     => 'stories',
          'controller' => 'index',
          'action'     => 'browse',
        )
      )
    );

    $router->addRoute('storiesShow',
      new Zend_Controller_Router_Route('oral-histories/:permalink',
        array(
          'module'     => 'stories',
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
