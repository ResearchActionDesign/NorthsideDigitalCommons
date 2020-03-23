<?php
/**
 * @package Images
 */

/**
 * Class Images_IndexController
 *
 * Renders root /images page.
 */
class Images_IndexController extends AbstractMCJCIndexController
{
  protected function getItemTypeId()
  {
    return 6;
  }
}
