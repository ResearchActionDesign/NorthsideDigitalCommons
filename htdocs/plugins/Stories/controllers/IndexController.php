<?php
/**
 * @package Stories
 */

/**
 * Class Stories_IndexController
 *
 * Renders root /people page.
 */
class Stories_IndexController extends Omeka_Controller_AbstractActionController
{
  public function init()
  {
    $this->_helper->db->setDefaultModelName('Item');
    $this->_browseRecordsPerPage = 10;
  }

  public function browseAction()
  {
    $db = $this->_helper->_db;

    // Filter by item type = Oral History.
    $this->setParam('type', StoriesPlugin::$ORAL_HISTORY_ITEM_TYPE);

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
