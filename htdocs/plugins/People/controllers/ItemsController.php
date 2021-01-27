<?php
/**
 * @package People
 */

const ORAL_HISTORY_ITEM_TYPE = 4;
const IMAGE_ITEM_TYPE = 6;
const PERSON_ITEM_TYPE = 12;

class People_ItemsController extends AbstractMCJCItemController
{
  protected function getItemTypeId()
  {
    return 12;
  }

  /**
   * Retrieve oral history records which "depict" this person and return as array.
   *
   * @param $person
   * @return array
   */
  private function _getOralHistories() {
    // First load the related items which this "depict" this item, so we can check their item type.
    array_walk($this->_objectRelations, function(&$relation) {
      if ($relation['relation_text'] === 'depicts') {
        $relation['item'] = get_record_by_id('item', $relation['subject_item_id']);
      }
    });

    $oralHistories = $this->filterForUniqueId(array_map(
      function($relation) { return $relation['item']; },
      array_filter(
        $this->_objectRelations,
        function($relation) { return $relation['relation_text'] === 'depicts' && array_key_exists('item', $relation) && $relation['item']['item_type_id'] === ORAL_HISTORY_ITEM_TYPE; }
        )
    ));

    // Show most recent items first.
    array_walk($oralHistories, function ($i) { $i->date = metadata($i, ['Dublin Core', 'Date']); });
    usort($oralHistories, function ($a, $b) { return $b->date <=> $a->date; });
    return $oralHistories;
  }

  /**
   * Retrieve any related content items other than oral history records depicting this person.
   * Exclude person items (those go in "in the community").
   *
   * @return array
   */
  protected function _getRelatedItems() {
    return array_filter(
      parent::_getRelatedItems(),
      function($item) {
        return $item->item_type_id !== ORAL_HISTORY_ITEM_TYPE
          && $item->item_type_id !== PERSON_ITEM_TYPE;
      }
    );
  }

  /**
   * Retrieve "in the community" section.
   *
   * "In the community" section contains family members (Person items with a family relationship), as well as
   * collections and exhibits which include/mention this person and topics.
   *
   * @return array
   */
  protected function _getInTheCommunity() {
    $familyRelationTexts = array_map(
      function($relation) { return $relation['label']; },
      MCJCDeploymentPlugin::$familyRelations
    );

    // Pull family.
    $family = $this->filterForUniqueId(array_map(
      function ($relation) {
        $item = $relation['item'];
        $item->inTheCommunity = 'Family';
        return $item;
      },
      array_filter(
        array_merge($this->_objectRelations, $this->_subjectRelations),
        function ($relation) use ($familyRelationTexts) {
          return array_key_exists('relation_text', $relation)
            && array_search($relation['relation_text'], $familyRelationTexts);
        }
      )
    ));

    // Pull collections.
    $collectionIds = array_unique(array_map(
      function ($relation) {
        return $relation['item']->collection_id;
      },
      array_filter(
        array_merge($this->_objectRelations, $this->_subjectRelations),
        function ($relation) {
          return array_key_exists('item', $relation)
            && $relation['item']->collection_id;
        }
      )
    ), SORT_NUMERIC);

    $collections = array_filter(array_map(
      function ($collectionId) {
        $collection = $this->getCollectionById($collectionId);
        if (!$collection->public) {
          return false;
        }
        $collection->inTheCommunity = 'Collection';
        return $collection;
      },
      $collectionIds
    ));

    // TODO: pull exhibits.
    // TODO: pull topics.

    return array_merge($family, $collections);
  }

  /**
   * Retrieve a single person and render it.
   *
   * Every request to this action must pass a record name in the 'name' parameter.
   */
  public function showAction()
  {
    parent::showAction();

    $this->view->assign(array(
      'oral_history_items' => $this->_getOralHistories(),
      'in_the_community_items' => $this->_getInTheCommunity(),
    ));
  }
}
