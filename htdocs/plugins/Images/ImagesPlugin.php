<?php

class ImagesPlugin extends Omeka_Plugin_AbstractPlugin
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

    $router->addRoute('imagesBrowse',
      new Zend_Controller_Router_Route('images',
        array(
          'module'     => 'images',
          'controller' => 'index',
          'action'     => 'browse',
        )
      )
    );

    $router->addRoute('imageShow',
      new Zend_Controller_Router_Route('images/:permalink',
        array(
          'module'     => 'images',
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
