<?php

/**
 * @package MCJCDeployment
 */

/**
 * Class AbstractMCJCItemController
 *
 * Abstract base class for an item controller which searches for an item by name from a human-readable URL
 * (e.g. 'stories/amanda-ashley').
 */
abstract class AbstractMCJCItemController extends Omeka_Controller_AbstractActionController
{
  protected $_subjectRelations = [];
  protected $_objectRelations = [];
  protected $_secondDegreeRelations = [];
  protected $_depictedItems = []; // Items depicted by this items.

  /**
   * Helper function to filter an array of Items for unique items, by ID.
   * @param array $items
   * @return array
   */
  protected function filterForUniqueId(array $items) {
    $unique = [];
    foreach ($items as $item) {
      if (isset($item->id)) {
        $unique[$item->id] = $item;
      }
    }
    return array_values($unique);
  }

  /**
   * Helper function to sort item relations.
   * @param array $items
   * @return array
   */
  protected function sortRelations(array $items) {
    static $northside_news_collection_id = 12; // TODO: Replace with non-hard-coded-value
    $sorted =  array_merge(
      array_filter($items, function ($item) use ($northside_news_collection_id) { return ($item['collection_id'] ?? null) !== $northside_news_collection_id;}),
      array_filter($items, function ($item) use ($northside_news_collection_id) { return ($item['collection_id'] ?? null) === $northside_news_collection_id;})
    );
    return $sorted;
  }

  /**
   * Helper function to load a single collection from the DB.
   *
   * @param int|null $collectionId
   * @return Omeka_Record_AbstractRecord|null
   */
  protected function getCollectionById(int $collectionId = null) {
    if (!$collectionId) return null;
    $table = $this->_helper->_db->getTable('Collection');
    return $table->find($collectionId);
  }

  protected function getPermalinkElementId() {
    static $permalinkElementId = false;
    if (!$permalinkElementId) {
      $elementTable = $this->_helper->_db->getTable('Element');
      $results = $elementTable->findBy(array(
        'name' => 'Permalink'
      ));
      $permalinkElementId = $results[0]['id'];
    }
    return $permalinkElementId;
  }

  // Return string or array representing item type(s) to be returned by this controller.
  abstract protected function getItemTypes();
  protected $_item;

  /**
   * Retrieve correct oral history item record for this action, based on 'name' parameter (only used on show action).
   *
   * @return object|bool
   * @throws Omeka_Controller_Exception_404
   * @uses Omeka_Controller_Action_Helper_Db::findById()
   */
  protected function _getRecordForShow()
  {
    $permalink = $this->getParam('permalink');
    $itemsTable = $this->_helper->_db->getTable();
    $namesTable = $this->_helper->_db->getTable('ElementText');

    $matchingNames = $namesTable->findBy([
      'record_type' => 'Item',
      'element_id' => $this->getPermalinkElementId(),
      'text' => $permalink,
    ], 1);

    if (count($matchingNames) === 0) {
      throw new Omeka_Controller_Exception_404;
    }

    $select = $itemsTable->getSelect();
    $itemsTable->filterByItemType($select, $this->getItemTypes());
    $itemsTable->filterByPublic($select, True); // Only return public records.
    $select->where("`items`.`id` = ?", $matchingNames[0]->record_id);
    $records = $itemsTable->fetchObjects($select);

    if (count($records)) {
      $this->_item = $records[0];
    } else {
      throw new Omeka_Controller_Exception_404;
    }
  }

  protected function _getRelations() {
    if (!$this->_item) return;
    $this->_subjectRelations = ItemRelationsPlugin::prepareSubjectRelations($this->_item);
    $this->_objectRelations = ItemRelationsPlugin::prepareObjectRelations($this->_item);

    // Create one array of just the IDs of related items.
    $existing_related_ids = array_filter(array_merge(
      array_map(function($item) { return $item['subject_item_id'] ?? false; }, $this->_subjectRelations),
      array_map(function($item) { return $item['object_item_id'] ?? false; }, $this->_objectRelations)
    ));

    // Pull second degree relations as well.
    foreach ($this->_subjectRelations as $subjectRelation) {
      $secondDegreeRelations = get_db()->getTable('ItemRelationsRelation')->findByObjectItemId($subjectRelation['object_item_id']);
      foreach ($secondDegreeRelations as $relation) {
        // Don't add an item to its own related items view, and don't re-add existing items.
        if ($relation['subject_item_id'] <> $this->_item->id && !array_key_exists($relation['subject_item_id'], $existing_related_ids)) {
          $this->_secondDegreeRelations[] = array(
            'id' => $relation['subject_item_id'],
          );
        }
      }
    }

    // First, load items for matching relations, as needed.
    // TODO: Can this be optimized?
    array_walk($this->_objectRelations, function(&$relation) {
      if (!array_key_exists('item', $relation)) $relation['item'] = get_record_by_id('item', $relation['subject_item_id']);
    });
    array_walk($this->_subjectRelations, function(&$relation) {
      if (!array_key_exists('item', $relation)) $relation['item'] = get_record_by_id('item', $relation['object_item_id']);
    });
    array_walk($this->_secondDegreeRelations, function(&$relation) {
      if (!array_key_exists('item', $relation)) $relation['item'] = get_record_by_id('item', $relation['id']);
    });

    // Filter out non-public items.
    $isPublic = function($relation) { return $relation['item'] && $relation['item']->public; };
    $this->_objectRelations = array_filter($this->_objectRelations, $isPublic);
    $this->_subjectRelations = array_filter($this->_subjectRelations, $isPublic);
    $this->_secondDegreeRelations = array_filter($this->_secondDegreeRelations, $isPublic);
  }

  protected function _getDepictedItems() {
    if (!count($this->_subjectRelations)) return;
    $this->_depictedItems = array_map(
      function($relation) {
        return $relation['item'];
      },
      array_filter(
      $this->_subjectRelations,
      function($relation) {
        return $relation['relation_text'] === 'depicts';
      })
    );
  }

  /**
   * Retrieve any related content items.
   *
   * @return array
   */
  protected function _getRelatedItems() {
    // TODO: Add second-degree relations and collections.
    return $this->sortRelations($this->filterForUniqueId(array_map(
      function ($relation) { return $relation['item']; },
      array_filter(
        array_merge($this->_objectRelations,
          array_filter($this->_subjectRelations,
            // Filter out items which will be in Depicted items array.
            function($relation) { return $relation['relation_text'] !== 'depicts'; }),
          $this->_secondDegreeRelations),
        function ($relation) {
          return array_key_exists('item', $relation)
            && $relation['item']['id'] !== $this->_item->id;
        }
      )
    )));
  }

  public function init()
  {
    $this->_helper->db->setDefaultModelName('Item');
    $this->_browseRecordsPerPage = null;
  }

  /**
   * Retrieve a single item and render it.
   *
   * Every request to this action must pass a record name in the 'name' parameter.
   *
   * @uses Omeka_Controller_Action_Helper_Db::getDefaultModelName()
   */
  public function showAction()
  {
    // Load person record based on name parameter.
    $singularName = $this->view->singularize($this->_helper->db->getDefaultModelName());
    $this->_getRecordForShow();
    if (!$this->_item) {
      throw new Omeka_Controller_Exception_404;
    }

    $this->_getRelations();
    $this->_getDepictedItems();

    $this->view->assign(array(
      $singularName => $this->_item,
      'related_items' => $this->_getRelatedItems(),
      'depicted_items' => $this->_depictedItems,
      'collection' => isset($this->_item->collection_id) ? $this->getCollectionById($this->_item->collection_id) : false,
    ));
  }

  public function browseAction()
  {
    throw new Omeka_Controller_Exception_404;
  }

  public function addAction()
  {
    throw new Omeka_Controller_Exception_404;
  }

  public function editAction()
  {
    throw new Omeka_Controller_Exception_404;
  }

  public function deleteAction()
  {
    throw new Omeka_Controller_Exception_404;
  }
}
