<?php
/**
 * @package Omeka\Plugins\McjcDeployment\views\helpers
 */
class McjcDeployment_View_Helper_GetRecordTypeIdentifier extends Zend_View_Helper_Abstract
{
  /**
   * Return the item type identifier (first part of URL) for custom Omeka URLs.
   */
  public function getRecordTypeIdentifier($record)
  {
    if (empty($record)) {
      return '';
    }

    // Prepare the record if passed as an array.
    if (is_array($record)) {
      if (empty($record['type']) || empty($record['id'])) {
        throw new Omeka_View_Exception(__('Invalid record passed as array while getting record URL.'));
      }
      $typeId = (integer)$record['item_type_id'];
    } // Get the current record from the view if passed as a string.
    elseif (is_string($record)) {
      $record = $this->view->getCurrentRecord($record);
      if (empty($record)) {
        throw new Omeka_View_Exception(__('Invalid record passed as string while getting record URL.'));
      }
      $typeId = $record->item_type_id;
    } // The record is an object.
    elseif (is_object($record)) {
      if (!($record instanceof Omeka_Record_AbstractRecord)) {
        throw new Omeka_View_Exception(__('Invalid record passed as object while getting record URL.'));
      }
      $typeId = $record->item_type_id;
    } // There is an error in parameters.
    else {
      throw new Omeka_View_Exception(__('Invalid record passed while getting record URL.'));
    }

    if (isset($typeId)) {
      // Hardcode these for performance.
      switch ($typeId) {
        case 1:
          return 'Text';
        case 3:
          return 'Moving Image';
        case 4:
          return 'Oral History';
        case 5:
          return 'Sound';
        case 6:
          return 'Still Image';
        case 7:
          return 'Website';
        case 8:
          return 'Event';
        case 9:
          return 'Email';
        case 10:
          return 'Lesson Plan';
        case 11:
          return 'Hyperlink';
        case 12:
          return 'Person';
        case 13:
          return 'Interactive Resource';
        case 14:
          return 'Dataset';
        case 15:
          return 'Physical Object';
        case 16:
          return 'Service';
        case 17:
          return 'Software';
        case 18:
          return 'Oral History Clip';
        case 19:
          return 'Project Interviews';
        case 20:
          return 'Theme';
      }

      // Otherwise, pull from DB
      $db = get_db();
      $bind = array($typeId);

      $sql = "
            SELECT item_types.name
            FROM {$db->ItemType} item_types
            WHERE item_types.id = ?
            LIMIT 1
        ";
      $identifier = $db->fetchOne($sql, $bind);

      // Keep only the identifier without the configured prefix.
      if ($identifier) {
        return $identifier;
      }
    }

    return '';
  }
}
