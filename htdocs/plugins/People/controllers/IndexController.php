<?php
/**
 * @package People
 */

/**
 * Class People_IndexController
 *
 * Renders root /people page.
 */
class People_IndexController extends Omeka_Controller_AbstractActionController
{
  public function init()
  {
    parent::init();
    $this->_helper->db->setDefaultModelName('Item');
    $this->_browseRecordsPerPage = 10;
  }

  public function browseAction()
  {
    $db = $this->_helper->_db;

    // Filter by item type = person.
    $this->setParam('type', 12);

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
      $this->redirect('/people');
    }
    elseif ($letter && $this->getParam('firstLetter') !== $letter) {
      $this->redirect("/people?firstLetter={$letter}");
    }

    if ($letter && $letter!== '') {
      $this->setParam('advanced', array(
        array(
          'joiner' => 'and',
          'element_id' => '49', // Hardcoded ID for Dublin Core: Subject. TODO: Replace with variable.
          'type' => 'starts with',
          'terms' => $letter,
        )
      ));
    }

    // TODO: Cache valid letters to avoid the extra DB call.
    // Find all valid letters and store this info to the view as well.
    $peopleTable = $db->getTable();
    $select = $peopleTable->getSelect();
    $peopleTable->filterByItemType($select, 'Person');
    $joinTable = "omeka_element_texts";

    // Note that 49 is hardcoded element ID for Dublin Core: Subject.
    $joinCondition = "{$joinTable}.record_id = items.id AND {$joinTable}.record_type = 'Item' AND {$joinTable}.element_id = '49'";
    $select->joinLeft($joinTable, $joinCondition, array('text'));
    $select->reset('columns');
    $select->columns(["LEFT(`{$joinTable}`.`text`, 1) AS first_letter"]);
    $select->order('first_letter');
    $select->distinct();
    $letters = array_filter($peopleTable->fetchCol($select));

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
