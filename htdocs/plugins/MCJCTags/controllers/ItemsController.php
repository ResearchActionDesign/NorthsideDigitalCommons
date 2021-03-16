<?php
/**
 * @package Stories
 */

class MCJCTags_ItemsController extends Omeka_Controller_AbstractActionController
{
  public function init()
  {
    $this->_helper->db->setDefaultModelName('Item');
  }

  public function showAction() {
    $tagName = $this->getParam('tagName');

    $pluralName = $this->view->pluralize($this->_helper->db->getDefaultModelName());
    $recordsPerPage = parent::_getBrowseRecordsPerPage($pluralName);
    $currentPage = $this->getParam('page', 1);

    $params = ['tag' => $tagName, 'sort_field' => 'Dublin Core,Title'];
    $records = $this->_helper->db->findBy($params, $recordsPerPage, $currentPage);
    $totalRecords = $this->_helper->db->count($params);

    // Add pagination data to the registry. Used by pagination_links().
    if ($recordsPerPage) {
      Zend_Registry::set('pagination', array(
        'page' => $currentPage,
        'per_page' => $recordsPerPage,
        'total_results' => $totalRecords,
      ));
    }

    $this->view->assign(['tag_name' => $tagName, 'items' => $records, 'total_results' => $totalRecords]);
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
