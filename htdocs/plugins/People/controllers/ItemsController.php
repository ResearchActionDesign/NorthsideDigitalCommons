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
  private $_secondDegreeRelations = [];

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
    $existing_related_ids = array_merge(
      array_map(function($item) { return $item['subject_item_id']; }, $this->_subjectRelations),
      array_map(function($item) { return $item['object_item_id']; }, $this->_objectRelations)
    );

    // Pull second degree relations as well.
    foreach ($this->_subjectRelations as $subjectRelation) {
      $secondDegreeRelations = get_db()->getTable('ItemRelationsRelation')->findByObjectItemId($subjectRelation['object_item_id']);
      foreach ($secondDegreeRelations as $relation) {

        // Don't add an item to its own related items view, and don't re-add existing items.
        if ($relation['subject_item_id'] <> $this->_person->id && !array_key_exists($relation['subject_item_id'], $existing_related_ids)) {
          $this->_secondDegreeRelations[] = array(
            'id' => $relation['subject_item_id'],
          );
        }
      }
    }

    foreach ($this->_objectRelations as $objectRelation) {
      $relatedItemIds[] = $objectRelation['subject_item_id'];
    }

    $relatedItemIds = array_unique($relatedItemIds);
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

    return array_map(
      function($relation) { return $relation['item']; },
      array_filter(
        $this->_objectRelations,
        function($relation) { return array_key_exists('item', $relation) && $relation['item']['item_type_id'] === ORAL_HISTORY_ITEM_TYPE; }
        )
    );
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
    array_walk($this->_secondDegreeRelations, function(&$relation) {
      if (!array_key_exists('item', $relation)) $relation['item'] = get_record_by_id('item', $relation['id']);
    });

    return array_map(
      function ($relation) { return $relation['item']; },
      array_filter(
        array_merge($this->_objectRelations, $this->_subjectRelations, $this->_secondDegreeRelations),
        function ($relation) {
          return array_key_exists('item', $relation)
            && $relation['item']['item_type_id'] !== ORAL_HISTORY_ITEM_TYPE
            && $relation['item']['item_type_id'] !== PERSON_ITEM_TYPE;
        }
      )
    );
  }

  /**
   * Retrieve "in the community" related items.
   *
   * @return array
   */
  private function _getInTheCommunity() {
    // TODO: Fill in In the Community Section.
    return array();
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
