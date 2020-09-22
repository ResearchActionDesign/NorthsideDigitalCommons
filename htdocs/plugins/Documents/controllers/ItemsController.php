<?php
/**
 * @package Documents
 */

class Documents_ItemsController extends AbstractMCJCItemController
{
  protected function getItemTypes() {
    return false;
  }

  protected function _getRecordForShow()
  {
    $permalink = $this->getParam('permalink');

    // If this matches just an item ID, return the item directly.
    if (preg_match('/[0-9]+/', $permalink)) {
      $itemsTable = $this->_helper->_db->getTable();
      $select = $itemsTable->getSelect();
      $itemsTable->filterByPublic($select, True); // Only return public records.
      $select->where("`items`.`id` = ?", $permalink);
      $records = $itemsTable->fetchObjects($select);
      if (count($records)) {
        $this->_item = $records[0];
        return;
      }
    }

    return parent::_getRecordForShow();
  }
}
