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
  $personName = metadata($item, array('Dublin Core', 'Title'));
  $url = url(array('name' => mb_strtolower(str_replace(' ', '-', $personName))), 'peopleShow');

  return "<a href='{$url}'>{$title}</a>";
}

/**
 * Helper function to load a single collection from the DB.
 *
 * @param int|null $collectionId
 * @param Omeka_Controller_Action_Helper_Db|null $db
 * @return Omeka_Record_AbstractRecord|null
 */
function people_get_collection_by_id(int $collectionId = null, Omeka_Controller_Action_Helper_Db $db = null) {
  if (!$collectionId || !$db) return null;
  $table = $db->getTable('Collection');
  return $table->find($collectionId);
}

/**
 * Helper function to filter an array of Items for unique items, by ID.
 * @param array $items
 * @return array
 */
function people_filter_for_unique_id(array $items) {
    $unique = [];
    foreach ($items as $item) {
      if (isset($item->id)) {
        $unique[$item->id] = $item;
      }
    }
    return array_values($unique);
}
