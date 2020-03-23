<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 9/27/16
 * Time: 6:34 PM
 */

require_once('vendor/autoload.php');

use \Rollbar\Rollbar;

class MCJCDeploymentPlugin extends Omeka_Plugin_AbstractPlugin
{

  protected $_hooks = array(
    'install',
    'upgrade',
    'initialize',
  );

  protected $_filters = array(
    'items_browse_per_page',
  );

  // Relationship types which should count as "family" in the "In the community" section.
  public static $familyRelations = array(
    array(
      'local_part' => 'parentOf',
      'label' => 'parent of',
      'description' => 'Someone who is a direct parent of someone else. Includes step-parents.'
    ),
    array(
      'local_part' => 'grandparentOf',
      'label' => 'grandparent of',
      'description' => ''
    ),
    array(
      'local_part' => 'childOf',
      'label' => 'child of',
      'description' => ''
    ),
    array(
      'local_part' => 'grandchildOf',
      'label' => 'grandchild of',
      'description' => ''
    ),
    array(
      'local_part' => 'partnerOf',
      'label' => 'partner of',
      'description' => 'Partner (depending on how people want to be referred to, consider using husbandOf/wifeOf)'
    ),
    array(
      'local_part' => 'husbandOf',
      'label' => 'husband of',
      'description' => ''
    ),
    array(
      'local_part' => 'wifeOf',
      'label' => 'wife of',
      'description' => ''
    ),
    array(
      'local_part' => 'siblingOf',
      'label' => 'sibling of',
      'description' => 'Sibling or half-sibling'
    ),
    array(
      'local_part' => 'kinOf',
      'label' => 'kin to',
      'description' => 'Any other family relationship *other than* parent/child, grandparent/grandchild, partner/spouse or sibling.'
    ));

  /**
   * Install the plugin.
   */
  public function hookInstall()
  {
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
    $typeField = $this->_db->getTable('Element')->findByElementSetNameAndElementName('Dublin Core', 'Type');
    $this->_db->insert('SimpleVocabTerm', array('element_id' => $typeField->id, 'terms' => implode("\n", $dublinCoreTypeValues)));
  }

  /**
   * Upgrade between versions of the site.
   *
   * Use this hook to make changes to config in a consistent way.
   * @param $oldVersion
   * @param $newVersion
   */
  public function hookUpgrade($params)
  {
    if ((double)$params['old_version'] < 2.11) {
      // Remove bibliography metadata type from person item.
      $personItemType = $this->_db->getTable('ItemType')->findByName('Person');
      $bibliographyElement = $this->_db->getTable('Element')
        ->findByElementSetNameAndElementName('Item Type Metadata',
          'Bibliography');
      $personItemType->removeElement($bibliographyElement);
      $personItemType->save();
    }

    if ((double)$params['old_version'] < 2.12) {
      $sql = "UPDATE `{$this->_db->getTable('Item')->getTableName()}` SET `item_type_id` = 6 WHERE `item_type_id` IS NULL";
      $this->_db->query($sql);
    }


    // Set Dublin Core Type to be equal to Item Type Metadata.
    if ((double)$params['old_version'] < 2.13) {
      $sql = "UPDATE `{$this->_db->getTable('ElementText')->getTableName()}` SET `text` = 'Still Image' WHERE `element_id` = 51 AND `text` = 'Photograph'";
      $this->_db->query($sql);

      $sql = $this->_db->getTable('Item')->getSelect()->where('`item_type_id` = 6')->assemble();
      $stillImages = $this->_db->query($sql)->fetchAll();
      foreach ($stillImages as $image_id) {
        $item = get_record_by_id('Item', $image_id['id']);
        if (empty(metadata($item, array('Dublin Core', 'Type')))) {
          $elementText = new ElementText();
          $elementText->record_id = $image_id['id'];
          $elementText->element_id = 51;
          $elementText->setText('Still Image');
          $elementText->record_type = 'Item';
          $elementText->save();
        }
      }

      // Set missing person item type.
      $sql = $this->_db->getTable('Item')->getSelect()->where('`item_type_id` = 12')->assemble();
      $stillImages = $this->_db->query($sql)->fetchAll();
      foreach ($stillImages as $image_id) {
        $item = get_record_by_id('Item', $image_id['id']);
        if (empty(metadata($item, array('Dublin Core', 'Type')))) {
          $elementText = new ElementText();
          $elementText->record_id = $image_id['id'];
          $elementText->element_id = 51;
          $elementText->setText('Person');
          $elementText->record_type = 'Item';
          $elementText->save();
        }
      }
    }

    // Clean out span tags from exhibit blocks.
    if ((double)$params['old_version'] < 2.14) {
      $sql = $this->_db->getTable('ExhibitPageBlock')->getSelect()->where("`text` like '%style%'")->assemble();
      $exhibitPageBlockIds = $this->_db->query($sql)->fetchAll();
      {
        foreach ($exhibitPageBlockIds as $id) {
          $exhibitPageBlock = get_record_by_id('ExhibitPageBlock', $id['id']);
          $exhibitPageBlock->text = preg_replace('/ style=\"[^\"]*\"/', '', $exhibitPageBlock->text);
          $exhibitPageBlock->text = preg_replace('/<\/?span>/', '', $exhibitPageBlock->text);
          $exhibitPageBlock->save();
        }
      }

      $sql = $this->_db->getTable('ExhibitBlockAttachment')->getSelect()->where("`caption` like '%style%'")->assemble();
      $exhibitAttachmentIds = $this->_db->query($sql)->fetchAll();
      {
        foreach ($exhibitAttachmentIds as $id) {
          $exhibitAttachment = get_record_by_id('ExhibitBlockAttachment', $id['id']);
          $exhibitAttachment->caption = preg_replace('/ style=\"[^\"]*\"/', '', $exhibitAttachment->caption);
          $exhibitAttachment->caption = preg_replace('/<\/?span>/', '', $exhibitAttachment->caption);
          $exhibitAttachment->save();
        }
      }
    }

    // Add new item relation types.
    // @see http://www.perceive.net/schemas/20021119/relationship/
    if ((double)$params['old_version'] < 2.15) {
      // Install custom version of FOAF relationship module.
      $vocabulary = new ItemRelationsVocabulary;
      $vocabulary->name = 'FOAF Relationship Module';
      $vocabulary->description = 'Module for extending the foaf:knows element. Customized by MCJC for Northside Digital Commons.';
      $vocabulary->namespace_prefix = 'rel';
      $vocabulary->namespace_uri = 'http://www.perceive.net/schemas/20021119/relationship/';
      $vocabulary->custom = 0;
      $vocabulary->save();

      $vocabularyId = $vocabulary->id;
      // Add our own terms to module.
      $relationProperties = array_merge(MCJCDeploymentPlugin::$familyRelations, array(
        array(
          'local_part' => 'friendOf',
          'label' => 'friend of',
          'description' => ''
        )),
      );

      foreach ($relationProperties as $formalProperty) {
        $property = new ItemRelationsProperty;
        $property->vocabulary_id = $vocabularyId;
        $property->local_part = $formalProperty['local_part'];
        $property->label = $formalProperty['label'];
        $property->description = $formalProperty['description'];
        $property->save();
      }
    }

    // Add new item relation type isPartOf.
    if ((double)$params['old_version'] < 2.16) {
      // Install custom version of Dublin Core relationship module.
      $vocabulary = new ItemRelationsVocabulary();
      $vocabulary->name = 'Dublin Core Relationship Module';
      $vocabulary->description = 'Dublin Core relationship types. Customized by MCJC for Northside Digital Commons.';
      $vocabulary->namespace_prefix = 'rel';
      $vocabulary->namespace_uri = 'https://www.dublincore.org/specifications/dublin-core/dcmi-terms/';
      $vocabulary->custom = 0;
      $vocabulary->save();

      $vocabularyId = $vocabulary->id;
      // Add our own terms to module.

      $relationProperties = array(
        array(
          'local_part' => 'isPartOf',
          'label' => 'Is part of',
          'description' => 'A related resource in which the described resource is physically or logically included. For example, a photograph that was taken or scanned during an oral history interview would have the isPartOf relationship to the interview item, or a photograph scanned from a scrapbook would have the isPartOf relationship to the scrapbook item.',
        ),
        array(
          'local_part' => 'hasPart',
          'label' => 'Has part',
          'description' => 'A related resource that is included either physically or logically in the described resource. This is the inverse of isPartOf.',
        )
      );

      foreach ($relationProperties as $formalProperty) {
        $property = new ItemRelationsProperty;
        $property->vocabulary_id = $vocabularyId;
        $property->local_part = $formalProperty['local_part'];
        $property->label = $formalProperty['label'];
        $property->description = $formalProperty['description'];
        $property->save();
      }
    }
  }

  /**
   * Add rollbar error handling on every request.
   */
  function hookInitialize()
  {
    try {
      $rollbar_token = Zend_Registry::get('bootstrap')->config->log->rollbar_access_token;

      if ($rollbar_token) {
        Rollbar::init([
          'access_token' => $rollbar_token,
          'environment' => APPLICATION_ENV,
          'root' => BASE_DIR,
        ]);
      }
    } catch (Exception $e) {
    }
  }

  public function filterItemsBrowsePerPage($number_items, $controller)
  {
    // If this query is specifically the browse-by-person view, show all people.
    if ($_SERVER['REQUEST_URI'] == '/items/browse?search=&advanced[0][element_id]=&advanced[0][type]=&advanced[0][terms]=&range=&collection=&type=12&tags=&featured=&exhibit=&submit_search=Search') {
      return 0;
    } else {
      return max($number_items, 20);
    }
  }
}
