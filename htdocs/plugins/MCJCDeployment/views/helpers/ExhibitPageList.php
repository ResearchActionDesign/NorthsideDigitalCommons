<?php

/**
 * View helper to return an array of pages in an exhibit.
 *
 * @package MCJCDeployment\View\Helper
 */
class MCJCDeployment_View_Helper_ExhibitPageList extends Zend_View_Helper_Abstract
{
  /**
   * Return the list of pages.
   *
   * @param Exhibit $exhibit
   * @param ExhibitPage|null $currentPage
   * @return string
   */
  public function exhibitPageList($exhibit, $currentPage = null)
  {
    $pages = $exhibit->PagesByParent;
    if (!($pages && isset($pages[0]))) {
      return false;
    }

    return $pages[0];
  }
}
