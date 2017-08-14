<?php
/**
 * Omeka
 *
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * View Helper for displaying files through Omeka.
 *
 * This will determine how to display any given file based on the MIME type
 * (Internet media type) of that file. Individual rendering agents are defined
 * by callbacks that are either contained within this class or defined by
 * plugins. Callbacks defined by plugins will override native class methods if
 * defined for existing MIME types. In order to define a rendering callback that
 * should be in the core of Omeka, define a method in this class and then make
 * sure that it responds to all the correct MIME types by modifying other
 * properties in this class.
 *
 * @package Omeka\View\Helper
 */
class MCJCDeployment_View_Helper_McjcFileMarkup extends Omeka_View_Helper_FileMarkup
{

  /**
   * Returns valid XHTML markup for displaying an image that has been stored
   * in Omeka. Overrides Omeka_View_Helper_FileMarkup->derivativeImage().
   *
   * @param File $file
   * @param array $options
   *   Options for customizing the display of images. Current options include: 'imageSize'
   * @return string HTML for display
   * @overrides
   */
  public function derivativeImage($file, array $options=array())
  {
    $html = '';
    $imgHtml = '';

    // Should we ever include more image sizes by default, this will be
    // easier to modify.
    $imgClasses = array(
      null => 'thumb',
      'thumbnail'=>'thumb',
      'square_thumbnail'=>'thumb',
      'fullsize'=>'full');
    $imageSize = $options['imageSize'];

    // If we can make an image from the given image size.
    if (array_key_exists($imageSize, $imgClasses) && strpos($file->type_os, 'PDF') === FALSE) {

      // A class is given to all of the images by default to make it
      // easier to style. This can be modified by passing it in as an
      // option, but recommended against. Can also modify alt text via an
      // option.
      $imgClass = $imgClasses[$imageSize];
      $imgAttributes = array_merge(array('class' => $imgClass),
        (array)$options['imgAttributes']);
      $imgHtml = $this->image_tag($file, $imgAttributes, $imageSize);
      $html .= !empty($imgHtml) ? $imgHtml : html_escape($file->original_filename);
    } else {
      // If this is a PDF, add link text instead of an image.
      if (stripos(html_escape($file->original_filename), 'tape') !== FALSE) {
        $html .= 'View Tape Log';
      } elseif (stripos(html_escape($file->original_filename), 'transcript') !== FALSE) {
        $html .= 'View Transcript';
      } elseif (stripos(html_escape($file->original_filename), 'abstract') !== FALSE) {
        $html .= 'View Abstract';
      }
    }

    // If there is no link image or text, use the filename.
    if ($html == '') {
      $html .= html_escape($file->original_filename);
    }
    $html = $this->_linkToFile($file, $options, $html);
    return $html;
  }

  public function mcjcFileMarkup($file, array $props=array(), $wrapperAttributes = array()) {
    return $this->fileMarkup($file, $props, $wrapperAttributes);
  }
}
