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

  protected function getDublinCoreTitleElementId() {
    return 50; // TODO: pull this dynamically?
  }

  // Return string representing item type to be returned by this controller.
  abstract protected function getItemType();
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
    $itemName = str_replace('-', ' ', $this->getParam('name'));
    $itemsTable = $this->_helper->_db->getTable();
    $namesTable = $this->_helper->_db->getTable('ElementText');

    $nameSelect = $namesTable->getSelectForFindBy([
      'record_type' => 'Item',
      'element_id' => $this->getDublinCoreTitleElementId(),
    ]);
    $nameSelect->where("`element_texts`.`text` LIKE ?", $itemName);
    $matchingNames = $namesTable->fetchObjects($nameSelect);

    $matchingIds = array_map(function ($record) {
      return $record->record_id;
    }, $matchingNames);

    if (count($matchingIds) === 0) {
      throw new Omeka_Controller_Exception_404;
    }

    $select = $itemsTable->getSelect();
    $itemsTable->filterByItemType($select, $this->getItemType());
    $select->where("`items`.`id` IN (?)", $matchingIds);
    $records = $itemsTable->fetchObjects($select);

    if (count($records)) {
      $this->_item = $records[0];
    }
  }

  public function init()
  {
    $this->_helper->db->setDefaultModelName('Item');
    $this->_browseRecordsPerPage = 10;
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

    $this->view->assign(array(
      $singularName => $this->_item,
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
