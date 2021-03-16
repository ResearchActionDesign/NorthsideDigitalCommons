<?php
/**
 * @package Images
 */

/**
 * Class Images_IndexController
 *
 * Renders root /images page.
 */
class Images_IndexController extends AbstractMCJCIndexController {
  protected $defaultSort = "Dublin Core,Title";

  protected function getItemTypeId()
  {
    return 6; // Note this is different from the Dublin Core item Type.
  }
}
