<?php

/**
 * Exhibit gallery view helper. Customized for MCJC
 *
 * @package MCJCDeployment\View\Helper
 */
class MCJCDeployment_View_Helper_ExhibitAttachmentGallery extends Zend_View_Helper_Abstract
{
    /**
     * Return the markup for a gallery of exhibit attachments.
     *
     * @uses ExhibitBuilder_View_Helper_ExhibitAttachment
     * @param ExhibitBlockAttachment[] $attachments
     * @param array $fileOptions
     * @param array $linkProps
     * @return string
     */
    public function exhibitAttachmentGallery($attachments, $fileOptions = array(), $linkProps = array())
    {
        if (!isset($fileOptions['imageSize'])) {
            $fileOptions['imageSize'] = 'thumbnail';
        }
        
        $html = '';
        foreach  ($attachments as $attachment) {
          $attachmentUrl = mcjc_url_for_item($attachment);
          $html .= '<a class="item has-description has-picture" href="' . $attachmentUrl . '">';
          $html .= $this->view->exhibitAttachment($attachment, $fileOptions, $linkProps, true);
          $html .= '</a>';
        }
    
        return apply_filters('exhibit_attachment_gallery_markup', $html,
            compact('attachments', 'fileOptions', 'linkProps'));
    }
}
