<?php

/**
 * MCJC customization of Omeka view Helper for displaying files through Omeka.
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
 * @package Omeka\Plugins\McjcDeployment\views\helpers
 *
 */
class MCJCDeployment_View_Helper_McjcImageTag extends Omeka_View_Helper_FileMarkup
{
  /**
   * Return a valid img tag for an image, do some custom filtering to remove placeholder images.
   *
   * Copied from FileMarkup.php and modified.
   *
   * @param Omeka_Record_AbstractRecord $record
   * @param array $attrs Image tag attributes
   * @param string $format Derivative image type (thumbnail, etc.)
   * @return string
   */
  public function mcjcImageTag($record, $attrs, $format)
  {
    if (!($record && $record instanceof Omeka_Record_AbstractRecord)) {
      return false;
    }

    // Use the default representative file.
    $file = $record->getFile();
    if (!$file) {
      return false;
    }

    // Don't display PDF file screenshots for transcript/tape log.
    if ($file->mime_type === "application/pdf") {
      if (stripos(html_escape($file->original_filename), 'tape') !== FALSE ||
        stripos(html_escape($file->original_filename), 'transcript') !== FALSE ||
        stripos(html_escape($file->original_filename), 'abstract') !== FALSE ||
        stripos(html_escape($file->original_filename), 'fieldnotes') !== FALSE) {
        return false;
      }
    }

    if (!$format) {
      $format = (get_option('use_square_thumbnail') == 1) ? 'square_thumbnail' : 'thumbnail';
    }

    if ($file->hasThumbnail()) {
      $uri = $file->getWebPath($format);
    } else {
      return false;
    }
    $attrs['src'] = $uri;

    /**
     * Determine alt attribute for images
     * Should use the following in this order:
     * 1. passed 'alt' prop
     * 2. first Dublin Core Title for $file
     * 3. original filename for $file
     */
    $alt = '';
    if (isset($attrs['alt'])) {
      $alt = $attrs['alt'];
    } elseif ($fileTitle = metadata($file, 'display title', array('no_escape' => true))) {
      $alt = $fileTitle;
    }
    $attrs['alt'] = $alt;

    if (isset($attrs['title'])) {
      $title = $attrs['title'];
    } else {
      $title = $alt;
    }
    $attrs['title'] = $title;

    $attrs = apply_filters('image_tag_attributes', $attrs, array(
      'record' => $record,
      'file' => $file,
      'format' => $format,
    ));

    // Build the img tag
    return '<img ' . tag_attributes($attrs) . '>';
  }
}
