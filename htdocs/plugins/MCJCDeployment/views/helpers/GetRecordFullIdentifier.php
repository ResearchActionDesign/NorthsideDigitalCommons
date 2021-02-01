<?php
/**
 * MCJC Deployment Get Record Full Identifier
 *
 * @todo Use a route name?
 * @see Omeka\View\Helper\RecordUrl.php
 */

/**
 * @package Omeka\Plugins\McjcDeployment\views\helpers
 */
class McjcDeployment_View_Helper_GetRecordFullIdentifier extends Zend_View_Helper_Abstract
{
    /**
     * Get clean url path of a record in the default or specified format.
     *
     * @param Record|array $record The use of an array improves the speed when
     * the identifiers are already known (without prefix). If the upper level
     * identifier is required and not set, it will be fetched. Examples for a
     * collection, an item and a file:
     * ['type' => 'Collection', 'id' => '1', 'identifier' => 'alpha']
     * ['type' => 'Item', 'id' => '2', 'identifier' => 'beta', 'collection' => ['id' => '1', 'identifier' => 'alpha']]
     * ['type' => 'File', 'id' => '3', 'identifier' => 'gamma', 'item' => ['id' => '2', 'identifier' => 'beta', ], 'collection' => ['id' => '1', 'identifier' => 'alpha']]
     * @param boolean $withMainPath
     * @param string $withBasePath Can be empty, 'admin', 'public' or
     * 'current'. If any, implies main path.
     * @param boolean $absoluteUrl If true, implies current / admin or public
     * path and main path.
     * @param string $format Format of the identifier (default one if empty).
     * @return string Full identifier of the record, if any, else empty string.
     */
    public function getRecordFullIdentifier(
        $record,
        $withMainPath = true,
        $withBasePath = 'current',
        $absolute = false,
        $format = null)
    {
        $identifier = is_array($record)
            ? $this->_getRecordFullIdentifierFromArray($record, $withMainPath, $withBasePath, $absolute, $format)
            : $this->_getRecordFullIdentifierFromRecord($record, $withMainPath, $withBasePath, $absolute, $format);

        return $identifier ?? parent::getRecordFullIdentifier($record, $withMainPath, $withBasePath, $absolute, $format);
    }

    public function getBaseRouteFromItemTypeIdentifier($item_type) {
      switch ($item_type) {
        case 'Still Image':
          return 'images';
        case 'Oral History':
        case 'Oral History Clip':
          return 'oral-histories';
        case 'Person':
          return 'people';
        case 'Theme':
          return 'topics';
        default:
          return 'documents';
      }
    }

    protected function _getRecordFullIdentifierFromRecord(
        $record,
        $withMainPath = true,
        $withBasePath = 'current',
        $absolute = false,
        $format = null)
    {
        $view = $this->view;

        switch (get_class($record)) {
          case 'Item':
            $type_identifier = $view->getRecordTypeIdentifier($record);
            $identifier = $view->getItemPermalink($record);
            if (empty($identifier)) {
              $identifier = $record->id;
            }
            return $this->_getUrlPath($absolute, $withMainPath, $withBasePath) . $this->getBaseRouteFromItemTypeIdentifier($type_identifier) . '/' . $identifier;
        }

        // Fallback to default Clean URL routing.
        return false;
    }

    // TODO: Adapt contents of getRecordFullIdentifierFromObject
    protected function _getRecordFullIdentifierFromArray(
        $record,
        $withMainPath = true,
        $withBasePath = 'current',
        $absolute = false,
        $format = null)
    {
        if (empty($record['type'])) {
            return '';
        }

        // Prepare the main identifier and save it in case of a generic need.
        if (!isset($record['identifier'])) {
            $record['identifier'] = $this->view->getRecordIdentifier($record);
        }

        switch ($record['type']) {
            case 'Item':
              $view = $this->view;
              $type_identifier = $view->getRecordTypeIdentifier($record);
              $identifier = $view->getItemPermalink($record);
              if (empty($identifier)) {
                $identifier = $record['id'];
              }
              return $this->_getUrlPath($absolute, $withMainPath, $withBasePath) . $this->getBaseRouteFromItemTypeIdentifier($type_identifier) . '/' . $identifier;
            }
        // Fallback to clean URL routing.
        return false;
    }

  /**
   * Return beginning of the record name if needed.
   *
   * @param boolean $withMainPath
   * @param boolean $withBasePath Implies main path.
   * @return string
   * The string ends with '/'.
   */
  protected function _getUrlPath($absolute, $withMainPath, $withBasePath)
  {
    if ($absolute) {
      $withBasePath = empty($withBasePath) ? 'current' : $withBasePath;
      $withMainPath = true;
    }
    elseif ($withBasePath) {
      $withMainPath = true;
    }

    switch ($withBasePath) {
      case 'public': $basePath = PUBLIC_BASE_URL; break;
      case 'admin': $basePath = ADMIN_BASE_URL; break;
      case 'current': $basePath = CURRENT_BASE_URL; break;
      default: $basePath = '';
    }

    $mainPath = $withMainPath ? get_option('clean_url_main_path') : '';

    return ($absolute ? $this->view->serverUrl() : '') . $basePath . '/' . $mainPath;
  }
}
