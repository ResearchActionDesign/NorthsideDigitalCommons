<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 9/27/16
 * Time: 6:34 PM
 */

require_once('vendor/autoload.php');

// TODO: Make this less hacky and use an actual namespace. Doesn't seem to currently work with the way Omeka + Zend are
// set up, though.
include 'Controller/AbstractMCJCItemController.php';
include 'Controller/AbstractMCJCIndexController.php';

use \Rollbar\Rollbar;

class MCJCDeploymentPlugin extends Omeka_Plugin_AbstractPlugin
{

  protected $_hooks = array(
    'before_save_element_text',
    'after_save_item',
    'admin_head',
    'initialize',
    'install',
    'public_head',
    'search_sql',
    'upgrade'
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
  public static $elementTypes = array(
    'Oral History' => 4,
    'Person' => 12,
    'Still Image' => 6,
    'Photograph' => 6,
  );

  /**
   * Return lowercase version of item title, with all special characters (except whitespace) removed, and all whitespace
   * replaced with dashes.
   *
   * @param $item
   * @return string
   */
  public static function getPermalinkFromItem($item) {
    $title = metadata($item, array('Dublin Core', 'Title'));
    if (!$title) {
      return false;
    }
    $title = trim($title);
    $permalink = mb_strtolower(preg_replace("/[^A-Za-z0-9\- ]/", '', $title));
    $permalink = preg_replace("/ +/", '-', $permalink);
    $permalink = preg_replace("/\-+/", '-', $permalink);

    if (mb_strlen($permalink) > 100) {
      $parts = explode('-', $permalink);
      $permalink = '';
      $i = 0;

      // Shorten long permalinks.
      while (mb_strlen($permalink) <= 100 && $i < count($parts)) {
        if ($permalink !== '') {
          $permalink .= '-';
        }
        $permalink .= $parts[$i];
        $i++;
      }
    }
    return $permalink;
  }

  public function getPermalinkElementId() {
    static $permalinkElementId = false;
    if (!$permalinkElementId) {
      $sql = $this->_db->getTable('Element')->getSelectForFindBy(array(
        'name' => 'Permalink'
      ));
      $results = $this->_db->query($sql)->fetchAll();
      $permalinkElementId = $results[0]['id'];
    }
    return $permalinkElementId;
  }

  protected function checkPermalinkValidForItem($permalink, $itemId) {
    $elementTextTable = $this->_db->getTable('ElementText');
    $sql = $elementTextTable->getSelectForFindBy(array(
      'element_id' => $this->getPermalinkElementId(),
      'text' => $permalink,
    ));
    $results = $elementTextTable->fetchObjects($sql);
    if (!$results || count($results) === 0) {
      return TRUE;
    }
    return !count(array_filter($results, function($elementText) use ($itemId) {
      return $elementText->record_id !== $itemId;
    }));
  }

  // Check permalink for uniqueness each time an item is saved.
  public function hookBeforeSaveElementText($args) {
    $elementTextItem = &$args['record'];
    if ($elementTextItem->element_id !== $this->getPermalinkElementId()) {
      return;
    }

    $permalinkBase = $elementTextItem->text;
    $permalink = $permalinkBase;

    // Check for duplicates.
    $index = 2;
    while (!$this->checkPermalinkValidForItem($permalink, $elementTextItem->record_id)) {
      // If this is an existing permalink, check if it already ends in a number before changing.
      if (ctype_digit($permalinkBase[mb_strlen($permalinkBase) - 1])) {
        $index = $permalinkBase[mb_strlen($permalinkBase) - 1];
        $permalinkBase = mb_substr($permalinkBase, 0, -2);
      }
      $permalink = "{$permalinkBase}-{$index}";
      $index++;
    }
    $elementTextItem->text = $permalink;
  }

  /**
   * Step through database and add permalink for any items which
   * are missing one.
   */
  protected function fillEmptyPermalinks() {
    // Populate permalink field for all elements where it doesn't exist.
    $existingPermalinks = [];
    $permalinkElementId = $this->getPermalinkElementId();

    // First pull person items. This ensures that if oral histories share the name of the person
    // the person item gets permalink with person name.
    $personSelect = $this->_db->getTable('Item')->getSelect()
      ->where('item_type_id = 12')
      ->joinLeft(['element_texts' => $this->_db->ElementText], "element_texts.record_id = items.id and element_texts.element_id = $permalinkElementId", [])
      ->where('element_texts.text is null')
      ->assemble();
    $items = $this->_db->query($personSelect)->fetchAll();

    // Then pull remainder.
    $remainderSelect = $this->_db->getTable('Item')->getSelect()
      ->where('item_type_id <> 12')
      ->joinLeft(['element_texts' => $this->_db->ElementText], "element_texts.record_id = items.id and element_texts.element_id = $permalinkElementId", [])
      ->where('element_texts.text is null')
      ->assemble();
    $items = array_merge($items, $this->_db->query($remainderSelect)->fetchAll());

    foreach ($items as $item) {
      $item = get_record_by_id('Item', $item['id']);
      if (empty(metadata($item, array('Dublin Core', 'Permalink')))) {
        $permalinkBase = MCJCDeploymentPlugin::getPermalinkFromItem($item);
        $permalink = $permalinkBase;
        $index = 2;
        while (array_search($permalink, $existingPermalinks)) {
          $permalink = "{$permalinkBase}-{$index}";
          $index++;
        }
        $existingPermalinks[] = $permalink;
        $elementText = new ElementText();
        $elementText->record_id = $item['id'];
        $elementText->element_id = $permalinkElementId;
        $elementText->setText($permalink);
        $elementText->record_type = 'Item';
        $elementText->save();
      }
    }
  }

  public function hookSearchSql($args) {
    // TODO: Make this user-editable.
    $search_replacements_base = [
      'St. Paul' => [
        'St. Paul',
        'St. Pauls',
        'St. Paul\'s',
        'Saint Paul',
        'Saint Pauls',
        'Saint Paul\'s',
        'St Paul',
        'St Pauls',
        'St Paul\'s',
      ],
        'St. Joseph' => [
          'St. Joseph',
          'St. Josephs',
          'St. Joseph\'s',
          'Saint Joseph',
          'Saint Josephs',
          'Saint Joseph\'s',
          'St Joseph',
          'St Josephs',
          'St Joseph\'s',
        ],
      ];

      $search_replacements = [];

      // Map base dict (more user-friendly format) to machine-usable format.
      foreach ($search_replacements_base as $canonical_phrase => $phrases) {
        array_map(
        function($phrase) use ($canonical_phrase, &$search_replacements) { $search_replacements[mb_strtolower($phrase)] = $canonical_phrase; },
        $phrases
      );
      }

      $querystr = mb_strtolower($args['params']['query'] ?? false);
      if ($querystr && array_key_exists($querystr, $search_replacements)) {
        $querystr = $search_replacements[$querystr];
        $args['params']['query'] = $querystr;
        $args['select']->reset(Zend_Db_Select::WHERE);
        $args['select']->distinct();

        // Substitute in new querystring in full-text search.
        $args['select']->where('MATCH (`search_texts`.`text`) AGAINST (?)', $querystr);

        // Also specify that the exact phrase must appear in search (otherwise MATCH __ LIKE will
        // ignore shorter phrases like St.
        $args['select']->where('`search_texts`.`text` LIKE ?', '%' . $querystr . '%');
      }
  }

  // Populate permalink for new items, and set Item Type.
  public function hookAfterSaveItem($args)
  {
    $item = &$args['record'];

    // Check item type.
    if (!$item->item_type_id) {
      $dublinCoreType = metadata($item, array('Dublin Core', 'Type'));
      if (!empty(MCJCDeploymentPlugin::$elementTypes[$dublinCoreType])) {
        $item->item_type_id = MCJCDeploymentPlugin::$elementTypes[$dublinCoreType];
      }
    }

    // Set permalink.
    if (!empty($item->Elements[$this->getPermalinkElementId()]) && !empty($item->Elements[$this->getPermalinkElementId()][0]['text'])) {
      return;
    }

    $permalinkBase = MCJCDeploymentPlugin::getPermalinkFromItem($item);
    $permalink = $permalinkBase;
    $index = 2;

    // Don't try to set a permalink if this element doesn't yet have text associated to it.
    if (!$permalink) {
      return;
    }

    while (!$this->checkPermalinkValidForItem($permalink, $item->id)) {
      $permalink = "{$permalinkBase}-{$index}";
      $index++;
    }

    $elementText = new ElementText();
    $elementText->record_id = $item->id;
    $elementText->element_id = $this->getPermalinkElementId();
    $elementText->setText($permalink);
    $elementText->record_type = 'Item';
    $elementText->save();
  }

    /**
   * Add rollbar error handling on every request.
   */
  public function hookInitialize()
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

  /** Load additional JS in header. */
  private function _head()
  {
    queue_js_file('speed.min', 'mediaelement-plugins/speed', array());
    queue_css_file('speed.min', 'all', false, 'mediaelement-plugins/speed');
    queue_js_file('skip-back.min', 'mediaelement-plugins/skip-back', array());
    queue_css_file('skip-back.min', 'all', false, 'mediaelement-plugins/skip-back');
    queue_js_file('jump-forward.min', 'mediaelement-plugins/jump-forward', array());
    queue_css_file('jump-forward.min', 'all', false, 'mediaelement-plugins/jump-forward');
  }

  public function hookAdminHead() {
    $this->_head();
  }

  public function hookPublicHead() {
    $this->_head();
  }

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
          ))
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

    // Add new permalink element to all items.
    if ((double)$params['old_version'] < 2.17) {
      $sql = $this->_db->getTable('Element')->getSelectForFindBy(array(
        'name' => 'Permalink'
      ));
      $results = $this->_db->query($sql)->fetchAll();

      // Create permalink element if it doesn't exist, add to Dublin core.
      $permalinkElementId = FALSE;
      if (!$results || count($results) === 0) {
        $permalink = new Element();
        $permalink->element_set_id = 1; // Dublin core.
        $permalink->name = 'Permalink';
        $permalink->description = 'URL for permalink to this item. Under normal circumstances, don\'t edit this field.';
        $permalink->order = 16;
        $permalink->save();
        $permalinkElementId = $permalink->id;
      } else {
        $permalinkElementId = $results[0]['id'];
      }

      if ($permalinkElementId) {
        $this->fillEmptyPermalinks();
      }
    }

    // Upgrade tags!
    if ((double)$params['old_version'] < 2.18) {
      require('util/TagUpdates.php');
      $this->updateTags($AUG_2020_TAG_UPDATES);
    }

    // Create item type for 'themes'
    if ((double)$params['old_version'] < 2.19) {
      if (!$this->_db->getTable('ItemType')->findByName('Theme')) {
        $theme = new ItemType();
        $theme->name = 'Theme';
        $theme->description = 'Themes are entry points into different topics in the site. For a theme to work properly, the item description should
        be an HTML write-up of the theme, like a finding guide / intro. The tags for the theme define which content items will be displayed on the theme page.';
        $theme->save();
      }
    }

    if ((double)$params['old_version'] < 2.20) {
      $this->fillEmptyPermalinks();
    }
  }

  protected function updateTags($tag_updates_array) {
    $logger = Zend_Registry::get('bootstrap')->getResource('Logger');
    foreach ($tag_updates_array as $item) {
      $tagQuery = $this->_db->getTable('Tag')->getSelect()->where('tags.name = ?', [$item['oldTag'],])->assemble();
      $tag = $this->_db->getTable('Tag')->fetchObject($tagQuery);

      if (!$tag) {
        if ($logger) {
          $logger->log('Tag not found: ' . $item['oldTag'],Zend_Log::WARN);
        }
        continue;
      }

      switch($item['action']) {
        case 'REMOVE':
          $tag->delete();
          break;
        case 'REPLACE':
          $tag->rename([$item['newTag'],]);
          break;
        case 'ADD':
          $tag->rename([$item['oldTag'], $item['newTag']]);
          break;
      }
    }
  }
}
