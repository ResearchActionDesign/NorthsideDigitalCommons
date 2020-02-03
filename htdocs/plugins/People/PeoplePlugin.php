<?php

class PeoplePlugin extends Omeka_Plugin_AbstractPlugin
{

  protected $_hooks = array(
    'define_routes',
//    'items_browse_sql',
  );

  protected $_filters = array(
//    'items_browse_per_page',
//    'public_navigation_main',
//    'item_search_filters',
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
          'controller' => 'items',
          'action'     => 'browse',
        )
      )
    );

    $router->addRoute('peopleShow',
      new Zend_Controller_Router_Route('people/show/:name',
        array(
          'module'     => 'people',
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

function people_get_link_to_item($title = null, $item = null) {
  if (!$item) {
    $item = get_current_record('item');
  }
  if (empty($title)) {
    $title = metadata($item, array('Dublin Core', 'Title'));
  }

  return '<a href="/people/show/'. html_escape(mb_strtolower(str_replace(' ', '-', $title))) . '">' . $title . '</a>';

}
