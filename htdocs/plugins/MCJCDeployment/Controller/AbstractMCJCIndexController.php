<?php
/**
 * @package MCJCDeployment
 */

abstract class AbstractMCJCIndexController extends Omeka_Controller_AbstractActionController
{
  abstract protected function getItemTypeId();

  public function getAllParams()
  {
    $params = parent::getAllParams();
    $params['public'] = True;
    return $params;
  }

  public function init()
  {
    $this->_helper->db->setDefaultModelName('Item');
    $this->_browseRecordsPerPage = 10;
  }

  public function browseAction()
  {
    $db = $this->_helper->_db;

    // Filter by item type.
    $this->setParam('type', $this->getItemTypeId());

    if (!$this->getParam('sort_field')) {
      $this->setParam('sort_field', 'Dublin Core,Subject');
    }

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
