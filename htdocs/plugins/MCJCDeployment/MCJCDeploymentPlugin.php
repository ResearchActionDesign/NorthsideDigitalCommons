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
    'upgrade',
  );

  protected $_filters = array(
    'items_browse_per_page',
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

  /**
   * Upgrade between versions of the site.
   *
   * Use this hook to make changes to config in a consistent way.
   * @param $oldVersion
   * @param $newVersion
   */
  public function hookUpgrade($params) {
    if ($params['old_version'] == '2.10') {
      // Remove bibliography metadata type from person item.
      $personItemType = $this->_db->getTable('ItemType')->findByName('person');
      $bibliographyElement = $this->_db->getTable('Element')->findByElementSetNameAndElementName('Item Type Metadata', 'Bibliography');
      $personItemType->removeElement($bibliographyElement);
      $personItemType->save();
    }
  }

  public function filterItemsBrowsePerPage($number_items, $controller) {
    // If this query is specifically the browse-by-person view, show all people.
    if ($_SERVER['REQUEST_URI'] == '/items/browse?search=&advanced[0][element_id]=&advanced[0][type]=&advanced[0][terms]=&range=&collection=&type=12&tags=&featured=&exhibit=&submit_search=Search') {
      return 0;
    }
    else {
      return max($number_items, 20);
    }
  }

}
