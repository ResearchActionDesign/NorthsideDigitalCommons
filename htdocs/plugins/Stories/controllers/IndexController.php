<?php
/**
 * @package Stories
 */

/**
 * Class Stories_IndexController
 *
 * Renders root /people page.
 */
class Stories_IndexController extends AbstractMCJCIndexController
{
  protected $defaultSort = 'Dublin Core,Subject';

  protected function getItemTypeId()
  {
    return [4, 18, 19];
  }
}
