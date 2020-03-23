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
          'module'     => 'mcjc-images',
          'controller' => 'index',
          'action'     => 'browse',
        )
      )
    );

    $router->addRoute('imagesShow',
      new Zend_Controller_Router_Route('images/:name',
        array(
          'module'     => 'mcjc-images',
          'controller' => 'items',
          'action'     => 'show'
        ),
        array(
          'name' => '[a-zA-z][a-zA-Z\-]*'
        )
      )
    );
  }
}
