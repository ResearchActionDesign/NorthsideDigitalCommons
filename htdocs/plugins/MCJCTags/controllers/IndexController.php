<?php
/**
 * @package MCJCTags
 */

/**
 * Class Stories_IndexController
 *
 * Renders root /tags page.
 *
 * Based on code in CleanUrl module.
 */
class MCJCTags_IndexController extends Omeka_Controller_AbstractActionController
{
  public function init()
  {
    // Reset script paths (will be regenerated in forwarded destination).
    $this->view->setScriptPath(null);
  }

  public function browseAction()
  {
    return $this->forward('tags', 'items', 'default', array(
      'module' => null,
      'controller' => 'items',
      'action' => 'tags',
      'record_type' => 'Item',
    ));
  }
}
