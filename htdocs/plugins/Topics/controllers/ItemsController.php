<?php
/**
 * @package Topics
 */

// TODO.
class Topics_ItemsController extends AbstractMCJCItemController
{
  protected function getItemTypeId() {
    return [20]; // Item type ID for themes.
  }

  protected function _getRelatedItems()
  {
    return [];
  }

  protected function getDepictedItems()
  {
    if (!$this->_item) return;
    $tags = array_map(function ($t) { return $t->name; }, $this->_item->Tags);

    if (!count($tags)) return;

    $pluralName = $this->view->pluralize($this->_helper->db->getDefaultModelName());
    $recordsPerPage = parent::_getBrowseRecordsPerPage($pluralName);
    $currentPage = $this->getParam('page', 1);

    $params = ['sort_field' => 'Dublin Core,Title', 'public' => True];

    // Default Item controller uses AND logic when you search for multiple tags, so have to copy this over here.
    $select = $this->_helper->db->getSelectForFindBy($params, $recordsPerPage, $currentPage);
    $i = 0;
    foreach ($tags as $tagName) {
      $subSelect = new Omeka_Db_Select;
      $subSelect->from(array('records_tags' => get_db()->RecordsTags), array('items.id' => 'records_tags.record_id'))
        ->joinInner(array('tags' => get_db()->Tag), 'tags.id = records_tags.tag_id', array())
        ->where('tags.name = ? AND records_tags.`record_type` = "Item"', trim($tagName));
      if ($i == 0) {
        $select->where('items.id IN (' . (string)$subSelect . ')');
      } else {
        $select->orwhere('items.id IN (' . (string)$subSelect . ')');
      }
      $i++;
    }
    $this->depictedItems = $this->_helper->db->fetchObjects($select);
  }
}
