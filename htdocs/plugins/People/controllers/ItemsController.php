<?php
/**
 * @package People
 */

// TODO: Pull these dynamically and store in plugin variables as part of install process.
const ORAL_HISTORY_ITEM_TYPE = 4;
const PERSON_ITEM_TYPE = 12;
const DUBLIN_CORE_TITLE_ELEMENT_ID = 50;


class People_ItemsController extends Omeka_Controller_AbstractActionController
{
  private $_person = false;
  private $_subjectRelations = [];
  private $_objectRelations = [];

  /**
   * Retrieve correct person record for this action, based on 'name' parameter (only used on show action).
   *
   * @return object|bool
   * @uses Omeka_Controller_Action_Helper_Db::findById()
   * @throws Omeka_Controller_Exception_404
   */
  private function _getPersonRecordForShow() {
    $personName = str_replace('-',' ', $this->getParam('name'));
    $peopleTable = $this->_helper->_db->getTable();
    $namesTable = $this->_helper->_db->getTable('ElementText');

    $nameSelect = $namesTable->getSelectForFindBy([
      'record_type' => 'Item',
      'element_id' => DUBLIN_CORE_TITLE_ELEMENT_ID,
    ]);
    $nameSelect->where("`element_texts`.`text` LIKE ?", $personName);
    $matchingNames = $namesTable->fetchObjects($nameSelect);

    $matchingIds = array_map(function ($record) { return $record->record_id; }, $matchingNames);

    if (count($matchingIds) === 0) {
      throw new Omeka_Controller_Exception_404;
    }

    $select = $peopleTable->getSelect();
    $peopleTable->filterByItemType($select, 'Person');
    $select->where("`items`.`id` IN (?)", $matchingIds);
    $records = $peopleTable->fetchObjects($select);

    if (count($records)) {
      $this->_person = $records[0];
    }
  }

  private function _getRelations() {
    if (!$this->_person) return;
    $this->_subjectRelations = ItemRelationsPlugin::prepareSubjectRelations($this->_person);
    $this->_objectRelations = ItemRelationsPlugin::prepareObjectRelations($this->_person);

    // Create one array of just the IDs of related items.
    $this->_relatedIds = array_merge(
      array_map(function($item) { return $item['subject_item_id']; }, $this->_subjectRelations),
      array_map(function($item) { return $item['object_item_id']; }, $this->_objectRelations)
    );
  }

  /**
   * Retrieve oral history records which "depict" this person and return as array.
   *
   * @param $person
   * @return array
   */
  private function _getOralHistories() {
    // First load the related items which this item "depicts", so we can check their item type.
    array_walk($this->_objectRelations, function(&$relation) {
      if ($relation['relation_text'] === 'depicts') {
        $relation['item'] = get_record_by_id('item', $relation['subject_item_id']);
      }
    });

    return people_filter_for_unique_id(array_map(
      function($relation) { return $relation['item']; },
      array_filter(
        $this->_objectRelations,
        function($relation) { return $relation['relation_text'] === 'depicts' && array_key_exists('item', $relation) && $relation['item']['item_type_id'] === ORAL_HISTORY_ITEM_TYPE; }
        )
    ));
  }

  /**
   * Retrieve any related content items other than oral history records depicting this person.
   * Exclude person items (those go in "in the community").
   *
   * @return array
   */
  private function _getRelatedItems() {
    // First, load items for matching relations, as needed.
    array_walk($this->_objectRelations, function(&$relation) {
      if (!array_key_exists('item', $relation)) $relation['item'] = get_record_by_id('item', $relation['subject_item_id']);
    });
    array_walk($this->_subjectRelations, function(&$relation) {
      if (!array_key_exists('item', $relation)) $relation['item'] = get_record_by_id('item', $relation['object_item_id']);
    });

    return people_filter_for_unique_id(array_map(
      function ($relation) { return $relation['item']; },
      array_filter(
        array_merge($this->_objectRelations, $this->_subjectRelations),
        function ($relation) {
          return array_key_exists('item', $relation)
            && $relation['item']['id'] !== $this->_person->id
            && $relation['item']['item_type_id'] !== ORAL_HISTORY_ITEM_TYPE
            && $relation['item']['item_type_id'] !== PERSON_ITEM_TYPE;
        }
      )
    ));
  }

  /**
   * Retrieve "in the community" section.
   *
   * "In the community" section contains family members (Person items with a family relationship), as well as
   * collections and exhibits which include/mention this person and topics.
   *
   * @return array
   */
  private function _getInTheCommunity() {
    $familyRelationTexts = array_map(
      function($relation) { return $relation['label']; },
      MCJCDeploymentPlugin::$familyRelations
    );

    // Pull family.
    $family = people_filter_for_unique_id(array_map(
      function ($relation) {
        $item = $relation['item'];
        $item->inTheCommunity = 'Family';
        return $item;
      },
      array_filter(
        array_merge($this->_objectRelations, $this->_subjectRelations),
        function ($relation) use ($familyRelationTexts) {
          return array_key_exists('relation_text', $relation)
            && array_search($relation['relation_text'], $familyRelationTexts);
        }
      )
    ));

    // Pull collections.
    $collectionIds = array_unique(array_map(
      function ($relation) {
        return $relation['item']->collection_id;
      },
      array_filter(
        array_merge($this->_objectRelations, $this->_subjectRelations),
        function ($relation) {
          return array_key_exists('item', $relation)
            && $relation['item']->collection_id;
        }
      )
    ), SORT_NUMERIC);

    $collections = array_map(
      function ($collectionId) {
        $collection = people_get_collection_by_id($collectionId, $this->_helper->_db);
        $collection->inTheCommunity = 'Collection';
        return $collection;
      },
      $collectionIds
    );

    // TODO: pull exhibits.
    // TODO: pull topics.

    return array_merge($family, $collections);
  }

  public function init()
  {
    $this->_helper->db->setDefaultModelName('Item');
    $this->_browseRecordsPerPage = 10;
  }

  /**
   * Retrieve a single person and render it.
   *
   * Every request to this action must pass a record name in the 'name' parameter.
   *
   * @uses Omeka_Controller_Action_Helper_Db::getDefaultModelName()
   */
  public function showAction()
  {
    // Load person record based on name parameter.
    $singularName = $this->view->singularize($this->_helper->db->getDefaultModelName());
    $this->_getPersonRecordForShow();
    if (!$this->_person) {
      throw new Omeka_Controller_Exception_404;
    }

    $this->_getRelations();

    $this->view->assign(array(
      $singularName => $this->_person,
      'oral_history_items' => $this->_getOralHistories(),
      'related_items' => $this->_getRelatedItems(),
      'in_the_community_items' => $this->_getInTheCommunity(),
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
