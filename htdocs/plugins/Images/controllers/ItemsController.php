<?php
/**
 * @package Images
 */

class Images_ItemsController extends AbstractMCJCItemController
{
  protected function getItemTypes() {
    return array('Still Image', 'Photograph');
  }
}
