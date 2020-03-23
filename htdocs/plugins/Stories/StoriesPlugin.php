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
      new Zend_Controller_Router_Route('stories',
        array(
          'module'     => 'stories',
          'controller' => 'index',
          'action'     => 'browse',
        )
      )
    );

    $router->addRoute('storiesShow',
      new Zend_Controller_Router_Route('stories/:name',
        array(
          'module'     => 'stories',
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

function stories_get_link_to_item($title = null, $item = null) {
  if (!$item) {
    $item = get_current_record('item');
  }
  $personName = metadata($item, array('Dublin Core', 'Title'));
  $url = url(array('name' => mb_strtolower(str_replace(' ', '-', $personName))), 'storiesShow');

  return "<a href='{$url}'>{$title}</a>";
}
