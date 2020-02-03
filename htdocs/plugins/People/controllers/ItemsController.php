<?php
/**
 * @package People
 */

class People_ItemsController extends Omeka_Controller_AbstractActionController
{
  public function init()
  {
    $this->_helper->db->setDefaultModelName('Item');
    $this->_browseRecordsPerPage = 10;
  }

  public function browseAction()
  {
    $this->setParam('type', 12);
    if (!$this->getParam('sort_field')) {
      $this->setParam('sort_field', 'Dublin Core:Subject');
    }

    //Must be logged in to view items specific to certain users
    if ($this->_getParam('user') && !$this->_helper->acl->isAllowed('browse', 'Users')) {
      $this->_helper->flashMessenger('May not browse by specific users.');
      $this->_setParam('user', null);
    }

    parent::browseAction();
  }

  /**
   * Retrieve a single person and render it.
   *
   * Every request to this action must pass a record name in the 'name' parameter.
   *
   *
   * @uses Omeka_Controller_Action_Helper_Db::getDefaultModelName()
   * @uses Omeka_Controller_Action_Helper_Db::findById()
   */
  public function showAction()
  {
    $singularName = $this->view->singularize($this->_helper->db->getDefaultModelName());
    $personName = str_replace('-',' ', $this->getParam('name'));
    $peopleTable = $this->_helper->_db->getTable();
    $namesTable = $this->_helper->_db->getTable('ElementText');

    $nameSelect = $namesTable->getSelectForFindBy([
      'record_type' => 'Item',
      'element_id' => 50,
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

    $this->view->assign(array($singularName => $records[0]));
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
