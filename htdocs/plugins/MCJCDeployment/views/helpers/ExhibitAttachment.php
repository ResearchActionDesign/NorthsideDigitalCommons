<?php

/**
 * Exhibit attachment view helper.
 *
 * Copies ExhibitBuilder\View\Helper\ExhibitBuilder_View_Helper_ExhibitAttachment exactly,
 * except forces $forceImage=false regardless of how the function was called, to override ExhibitBuilder handling
 * of audio attachments.
 *
 * @package Omeka\Plugins\McjcDeployment\views\helpers
 */
class MCJCDeployment_View_Helper_ExhibitAttachment extends Zend_View_Helper_Abstract {
  
  /**
   * Return the markup for displaying an exhibit attachment.
   *
   * @param ExhibitBlockAttachment $attachment
   * @param array $fileOptions Array of options for file_markup
   * @param array $linkProps Array of options for exhibit_builder_link_to_exhibit_item
   * @param boolean $forceImage Whether to display the attachment as an image
   *  always Defaults to true.
   * @return string
   */
  public function exhibitAttachment($attachment, $fileOptions = array(), $linkProps = array(), $forceImage = true)
  {
    $item = $attachment->getItem();
    $file = $attachment->getFile();

    if ($file) {
      if (!isset($fileOptions['imgAttributes']['alt'])) {
        $fileOptions['imgAttributes']['alt'] = metadata($item, array('Dublin Core', 'Title'), array('no_escape' => true));
      }

      if ($forceImage) {
        $imageSize = isset($fileOptions['imageSize'])
          ? $fileOptions['imageSize']
          : 'square_thumbnail';
        $html = file_image($imageSize, $fileOptions['imgAttributes'], $file);
      } else {
        if (!isset($fileOptions['linkAttributes']['href'])) {
          $fileOptions['linkAttributes']['href'] = exhibit_builder_exhibit_item_uri($item);
        }
        $html = file_markup($file, $fileOptions, null);
      }
    } else if($item) {
      $html = exhibit_builder_link_to_exhibit_item(null, $linkProps, $item);
    }

    if ($item) {
      $attachment['title'] = metadata($item, array('Dublin Core', 'Title'), array('no_escape' => true));
    }

    // Don't show a caption if we couldn't show the Item or File at all
    if (isset($html)) {
      $html .= $this->view->exhibitAttachmentCaption($attachment);
    } else {
      $html = '';
    }

    return apply_filters('exhibit_attachment_markup', $html,
      compact('attachment', 'fileOptions', 'linkProps', 'forceImage')
    );
  }

  /**
   * Return the markup for an attachment's caption.
   *
   * @param ExhibitBlockAttachment $attachment
   * @return string
   */
  protected function _caption($attachment)
  {
    if (!is_string($attachment['caption']) || $attachment['caption'] == '') {
      return '';
    }

    $html = '<div class="exhibit-item-caption">'
      . $attachment['caption']
      . '</div>';

    return apply_filters('exhibit_attachment_caption', $html, array(
      'attachment' => $attachment
    ));
  }
}
