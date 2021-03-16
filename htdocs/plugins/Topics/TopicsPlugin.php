<?php

class TopicsPlugin extends Omeka_Plugin_AbstractPlugin
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

    $router->addRoute('topicsBrowse',
      new Zend_Controller_Router_Route('topics',
        array(
          'module'     => 'topics',
          'controller' => 'index',
          'action'     => 'browse',
        )
      )
    );

    $router->addRoute('topicsShow',
      new Zend_Controller_Router_Route('topics/:permalink',
        array(
          'module'     => 'topics',
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
