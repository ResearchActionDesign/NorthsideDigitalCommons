<?php
/**
 * @package Places
 */

/**
 * Class Places_IndexController
 *
 * Renders root / places page.
 */
class Places_IndexController extends Omeka_Controller_AbstractActionController
{
  public function init()
  {
    parent::init();
    $this->_helper->db->setDefaultModelName('Item');
    $this->_browseRecordsPerPage = 24; // TODO: Add an AJAX controller for infinite scroll.
  }

  public function browseAction()
  {
    $db = $this->_helper->_db;

    // Filter by item type = place.
    $this->setParam('type', 22);

    if (!$this->getParam('sort_field')) {
      $this->setParam('sort_field', 'Dublin Core,Subject');
    }

    // Filter by first letter of last name

    // Validate parameter
    $letter = ucfirst((string)($this->getParam('firstLetter')));
    if (strlen($letter) > 1) {
      $letter = substr($letter, 0, 1);
    }
    if (!ctype_alpha($letter)) {
      $letter = null;
    }

    // If the param is invalid, redirect.
    if (!$letter && $this->getParam('firstLetter')) {
      $this->redirect('/places');
    }
    elseif ($letter && $this->getParam('firstLetter') !== $letter) {
      $this->redirect("/places?firstLetter={$letter}");
    }

    if ($letter && $letter!== '') {
      $this->setParam('advanced', array(
        array(
          'joiner' => 'and',
          'element_id' => '50', // Hardcoded ID for Title. TODO: Replace with variable.
          'type' => 'starts with',
          'terms' => $letter,
        )
      ));
    }

    // TODO: Cache valid letters to avoid the extra DB call.
    // Find all valid letters and store this info to the view as well.
    $placesTable = $db->getTable();
    $select = $placesTable->getSelect();
    $placesTable->filterByItemType($select, 'Person');
    $joinTable = $db->getTable('ElementText')->getTableName();

    // Note that 50 is hardcoded element ID for Title.
    $joinCondition = "{$joinTable}.record_id = items.id AND {$joinTable}.record_type = 'Item' AND {$joinTable}.element_id = '50'";
    $select->joinLeft($joinTable, $joinCondition, array('text'));
    $select->reset('columns');
    $select->columns(["LEFT(`{$joinTable}`.`text`, 1) AS first_letter"]);
    $select->order('first_letter');
    $select->distinct();
    $letters = array_filter($placesTable->fetchCol($select));

    // Store current letter and all valid letters in view.
    $this->view->assign(array(
      'cur_letter' => $letter,
      'letters' => $letters,
    ));

    // Must be logged in to view items specific to certain users. (Boilerplate from other ItemController classes).
    if ($this->_getParam('user') && !$this->_helper->acl->isAllowed('browse', 'Users')) {
      $this->_helper->flashMessenger('May not browse by specific users.');
      $this->_setParam('user', null);
    }

    parent::browseAction();
  }

  public function showAction()
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
