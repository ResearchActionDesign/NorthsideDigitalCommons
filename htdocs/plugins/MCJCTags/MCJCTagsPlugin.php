<?php

class MCJCTagsPlugin extends Omeka_Plugin_AbstractPlugin
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

    $router->addRoute('tagsBrowse',
      new Zend_Controller_Router_Route('tags',
        array(
          'module'     => 'mcjc-tags',
          'controller' => 'index',
          'action'     => 'browse',
        )
      )
    );

    $router->addRoute('tagsShow',
      new Zend_Controller_Router_Route('tags/:tagName',
        array(
          'module'     => 'mcjc-tags',
          'controller' => 'items',
          'action'     => 'show'
        )
      )
    );
  }
}
