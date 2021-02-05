<?php

class RedirectPlugin extends Omeka_Plugin_AbstractPlugin
{

  protected $_hooks = array(
    'define_routes',
  );

  /**
   * Adds a stub /items/show/<id> controller to redirect these pages to the
   * correct URL.
   */
  public function hookDefineRoutes($args)
  {
    $router = $args['router'];

    $router->addRoute('itemsShow',
      new Zend_Controller_Router_Route('items/show/:id',
        array(
          'module'     => 'redirect',
          'controller' => 'items',
          'action'     => 'show',
        ),
        array(
          'id' => '[0-9]+'
        )
      )
    );
  }
}
