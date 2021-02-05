<?php

class Redirect_ItemsController extends Omeka_Controller_AbstractActionController {
  public function init()
  {
    $this->_helper->db->setDefaultModelName('Item');
    $this->_browseRecordsPerPage = null;
  }

  public function browseAction()
  {
    $singularName = $this->view->singularize($this->_helper->db->getDefaultModelName());
    $record = $this->_helper->db->findById();
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

  public function showAction() {
    $singularName = $this->view->singularize($this->_helper->db->getDefaultModelName());
    $record = $this->_helper->db->findById();
    $record_url = $this->view->getRecordFullIdentifier($record);
    if (stripos($record_url, 'items' !== 0)) {
      $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
      return $redirector->gotoUrlAndExit($record_url);
    }
    throw new Omeka_Controller_Exception_404;
  }
}
