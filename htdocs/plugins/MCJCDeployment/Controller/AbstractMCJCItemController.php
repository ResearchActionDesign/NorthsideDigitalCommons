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
    $itemsTable->filterByItemType($select, $this->getItemType());
    $select->where("`items`.`id` = ?", $matchingNames[0]->record_id);
    $records = $itemsTable->fetchObjects($select);

    if (count($records)) {
      $this->_item = $records[0];
    } else {
      throw new Omeka_Controller_Exception_404;
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
