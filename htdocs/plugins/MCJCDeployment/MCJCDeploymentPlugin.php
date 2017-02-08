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

    if ((double) $params['old_version'] < 2.12) {
      // Autofill blank descriptions for oral history items.
      $oralHistoryItemType = $this->_db->getTable('ItemType')->findByName('Oral History');
      $items = $this->_db->getTable('Item')->findBySql("item_type_id={$oralHistoryItemType->id}");
      foreach ($items as $item) {
        $description = $item->getElementTexts('Dublin Core', 'Description');
        if ($description == NULL || empty($description[0]->text)) {
          // Need to convert interviewer names from "Last name, first name" to "First name Last name".
          $convertNameFormat = function($s) {
            if (strpos($s, ',') !== FALSE) {
              return trim(substr(strstr($s,
                ','),
                1)) . ' ' . trim(strstr($s,
                ',',
                TRUE));
            }
            else {
              return $s;
            }
          };
          $interviewers = array_map(function($a) use ($convertNameFormat) { return $convertNameFormat($a->text); }, $item->getElementTexts('Item Type Metadata', 'Interviewer'));
          $date = $item->getElementTexts('Item Type Metadata', 'Interview Date');
          $text = '';
          if (empty($interviewers)) {
            if (empty($date)) {
              continue;
            }
            else {
              $text = sprintf('Interviewed on %s', date_format(date_create($date[0]->text), 'F j, Y'));
            }
          }
          elseif (empty($date)) {
            $text = sprintf('Interviewed by %s', implode(' and ', $interviewers));
          }
          else {
            $text = sprintf('Interviewed by %s on %s', implode(' and ', $interviewers), date_format(date_create($date[0]->text), 'F j, Y'));
          }

          // Populate description.
          $item->addElementTextsByArray(
            array(
              'Dublin Core' => array(
                'Description' => array(
                  array(
                    'text' => $text,
                  )
                )
              )
            )
          );
          $item->save();
        }
      }
    }

    if ((double) $params['old_version'] < 2.11) {
      // Remove bibliography metadata type from person item.
      $personItemType = $this->_db->getTable('ItemType')->findByName('Person');
      $bibliographyElement = $this->_db->getTable('Element')
        ->findByElementSetNameAndElementName('Item Type Metadata',
          'Bibliography');
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
