<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 9/27/16
 * Time: 6:34 PM
 */

class BrowseByPersonPlugin extends Omeka_Plugin_AbstractPlugin {

  protected $_hooks = array('install');

  /**
   * Install the plugin.
   */
  public function hookInstall() {

    // Add the new person element to the Dublin Core element set.
    $elementSet = $this->_db->getTable('ElementSet')->findByName('Dublin Core');
    if (!$this->_db->getTable('Element')->findBySql("elements.name='Person'")) {
      $sql = "INSERT INTO `{$this->_db->Element}` (`element_set_id`, `name`, `description`) 
            VALUES (?, ?, ?)";
      $this->_db->query($sql,
        array($elementSet->id, 'Person', 'Person associated with this item.'));
    }

    $personFieldId = $this->_db->getTable('Element')->findBySql('element.name = ?', array('Person'), true)->id;

    // Create the Person taxonomy.
    $sql = "INSERT INTO `{$this->db->Taxonomy}` (`name`) VALUES ('Person')";
    $this->_db->query($sql);
    $personTaxonomyId = $this->_db->getTable('Taxonomy')->findBySql("name IS 'Person'")->id;

    // Set the element type to taxonomy.
    $sql = "INSERT INTO `{$this->db->ElementType}` (`element_id`, `element_type`, `element_type_options`)
            VALUES (?, ?, ?)";
    $this->_db->query($sql, array($personFieldId, 'taxonomy-term', '{"taxonomy_id":"' . $personTaxonomyId . '"}'));
  }

}