<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 9/27/16
 * Time: 6:34 PM
 */

class MCJCDeploymentPlugin extends Omeka_Plugin_AbstractPlugin {

  protected $_hooks = array(
    'install',
  );

  /**
   * Install the plugin.
   */
  public function hookInstall() {
    // Remove item relation vocabularies except for FOAF.
    $foafVocab = $this->_db->getTable('ItemRelationsVocabulary')->findBySql("name='FOAF'");
    if ($foafVocab && count($foafVocab) > 0) {
      // Delete non-FOAF item relations.
      $sql = "DELETE FROM `{$this->_db->getTable('ItemRelationsProperty')->getTableName()}` WHERE `vocabulary_id` <> ?";
      $this->_db->query($sql, array($foafVocab[0]->id));

      // Delete non-FOAF item relation vocabularies.
      $sql = "DELETE FROM `{$this->_db->getTable('ItemRelationsVocabulary')->getTableName()}` WHERE `id` <> ?";
      $this->_db->query($sql, array($foafVocab[0]->id));
    }

    // Populate Dublin Core type field. These values should come from the
    // controlled item type vocabulary provided by Omeka:
    // Document, Moving Image, Oral History, Sound, Still Image, Website, Event,
    // Email, Lesson Plan, Hyperlink, Person, or Interactive Resource.
    $dublinCoreTypeValues = array('Oral History', 'Still Image', 'Person', 'Moving Image', 'Document');
    $typeField= $this->_db->getTable('Element')->findByElementSetNameAndElementName('Dublin Core', 'Type');
    $this->_db->insert('SimpleVocabTerm', array('element_id' => $typeField->id, 'terms' => implode("\n", $dublinCoreTypeValues)));
  }


}