<?php
/**
 * @package Omeka\Plugins\McjcDeployment\views\helpers
 */
class McjcDeployment_View_Helper_GetItemPermalink extends Zend_View_Helper_Abstract
{
    protected function getPermalinkElementId() {
      return 56; // TODO: make this dynamic.
    }

    /**
     * Return the permalink of a record, if any. It can be sanitized.
     *
     * @param Omeka_Record_AbstractRecord|string|array $record If array, the
     * type and the id: ['type' => 'Item', 'id' => 1].
     * @param boolean $rawEncoded Sanitize the identifier for http or not.
     * @return string Identifier of the record, if any, else empty string.
     */
    public function getItemPermalink($record, $rawEncoded = true)
    {
        if (empty($record)) {
            return '';
        }

        // Prepare the record if passed as an array.
        if (is_array($record)) {
            if (empty($record['type']) || empty($record['id'])) {
                throw new Omeka_View_Exception(__('Invalid record passed as array while getting record URL.'));
            }
            $recordType = $record['type'];
            $recordId = (integer) $record['id'];
        }
        // Get the current record from the view if passed as a string.
        elseif (is_string($record)) {
            $record = $this->view->getCurrentRecord($record);
            if (empty($record)) {
                throw new Omeka_View_Exception(__('Invalid record passed as string while getting record URL.'));
            }
            $recordType = get_class($record);
            $recordId = $record->id;
        }

        // The record is an object.
        elseif (is_object($record)) {
            if (!($record instanceof Omeka_Record_AbstractRecord)) {
                throw new Omeka_View_Exception(__('Invalid record passed as object while getting record URL.'));
            }
            $recordType = get_class($record);
            $recordId = $record->id;
        }
        // There is an error in parameters.
        else {
            throw new Omeka_View_Exception(__('Invalid record passed while getting record URL.'));
        }

        // Use a direct query in order to improve speed.
        $db = get_db();
        $elementId = (integer) $this->getPermalinkElementId();
        $bind = array($recordType, $recordId);

        $checkUnspace = false;

        $sqlWhereText = '';

        $sql = "
            SELECT element_texts.text
            FROM {$db->ElementText} element_texts
            WHERE element_texts.element_id = '$elementId'
                AND element_texts.record_type = ?
                AND element_texts.record_id = ?
                $sqlWhereText
            ORDER BY element_texts.id
            LIMIT 1
        ";
        $identifier = $db->fetchOne($sql, $bind);

        // Keep only the identifier without the configured prefix.
        if ($identifier) {
            return $rawEncoded
                ? rawurlencode($identifier)
                : $identifier;
        }

        return '';
    }
}
