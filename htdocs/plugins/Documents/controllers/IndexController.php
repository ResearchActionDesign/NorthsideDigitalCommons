<?php
/**
 * @package Images
 */

/**
 * Class Images_IndexController
 *
 * Renders root /images page.
 */
class Documents_IndexController extends AbstractMCJCIndexController
{
  protected function getItemTypeId()
  {
    // @see McjcDeployment/views/helpers/GetRecordTypeIdentifiers for hardcoded list of types here.
    return [1, 3, 5, 7, 8, 9, 19, 11, 13, 14, 15, 16, 17];
  }
}
