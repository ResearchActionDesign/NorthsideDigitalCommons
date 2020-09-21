<?php
/**
 * @package Topics
 */

/**
 * Class Topics_IndexController
 *
 * Renders root /images page.
 */
class Topics_IndexController extends AbstractMCJCIndexController
{
  public function init() {

  }

  // Unneeded.
  public function getItemTypeId() {
    return null;
  }

  protected function applySort() {
    $pluralName = $this->view->pluralize($this->_helper->db->getDefaultModelName());

    // Apply controller-provided default sort parameters
    if (!$this->_getParam('sort_field')) {
      $defaultSort = apply_filters("{$pluralName}_browse_default_sort",
        $this->_getBrowseDefaultSort(),
        array('params' => $this->getAllParams())
      );
      if (is_array($defaultSort) && isset($defaultSort[0])) {
        $this->setParam('sort_field', $defaultSort[0]);

        if (isset($defaultSort[1])) {
          $this->setParam('sort_dir', $defaultSort[1]);
        }
      }
    }
  }

  /**
   * Retrieve and render a set of records, combining Exhibits, Collections, and Topics.
   *
   * Adaptation of Omeka_Controller_AbstractActionController::browseAction.
   */
  public function browseAction()
  {
    // Respect only GET parameters when browsing.
    $this->getRequest()->setParamSources(array('_GET'));

    $params = $this->getAllParams();
    $recordsPerPage = 24;
    $currentPage = $this->getParam('page', 1);

    // Get collections.
    $this->_helper->db->setDefaultModelName('Collection');
    $this->applySort();
    $collectionRecords = $this->_helper->db->findBy($params, $recordsPerPage, $currentPage);
    if ($collectionRecords) {
      array_walk($collectionRecords, function(&$item) { $item->topicType = 'Collection'; });
    } else {
      $collectionRecords = [];
    }
    $totalRecords = $this->_helper->db->count($params);

    // Get exhibits.
    $this->_helper->db->setDefaultModelName('Exhibit');
    $this->applySort();
    $exhibitRecords = $this->_helper->db->findBy($params, $recordsPerPage, $currentPage);
    if ($exhibitRecords) {
      array_walk($exhibitRecords, function(&$item) { $item->topicType = 'Exhibit'; });
    } else {
      $exhibitRecords = [];
    }
    $totalRecords += $this->_helper->db->count($params);

    // TODO: get themes

    $topicsRecords = array_merge($collectionRecords, $exhibitRecords);

    // Assign titles.
    array_walk($topicsRecords, function(&$item) {
      if (!isset($item->title)) {
        $item->title = metadata($item, 'display_title');
      }
    });

    $sortFunction = false;
    $reverseSort = ($params['sort_dir'] ?? '') === 'd';
    switch ($params['sort_field'] ?? '') {
      case 'added':
        $sortFunction = function($a, $b) use ($reverseSort) {
          if ($a['added'] == $b['added']) {
            return 0;
          }
          return (strtotime($a['added']) < strtotime($b['added']) ? -1 : 1) * ($reverseSort ? -1 : 1);
        };
        break;
      case 'Dublin Core,Title':
        $sortFunction = function($a, $b) use ($reverseSort) {
          if ($a->title === $b->title) {
            return 0;
          }
          return ($a->title < $b->title ? -1 : 1) * ($reverseSort ? -1 : 1);
        };
        break;
    }

    if ($sortFunction) {
      usort($topicsRecords, $sortFunction);
    }

    // Add pagination data to the registry. Used by pagination_links().
    if ($recordsPerPage) {
      Zend_Registry::set('pagination', array(
        'page' => $currentPage,
        'per_page' => $recordsPerPage,
        'total_results' => $totalRecords,
      ));
    }

    $this->view->assign(array(
      'topics' => $topicsRecords,
      'total_results' => $totalRecords));
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
