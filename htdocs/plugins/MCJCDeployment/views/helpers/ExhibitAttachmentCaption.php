<?php

/**
 * MCJC CUstomization of Exhibit attachment caption view helper.
 *
 * @package MCJCDeployment\View\Helper
 */
class MCJCDeployment_View_Helper_ExhibitAttachmentCaption extends Zend_View_Helper_Abstract
{
    /**
     * Return the markup for an exhibit attachment's caption.
     *
     * @param ExhibitBlockAttachment $attachment
     * @return string
     */
    public function exhibitAttachmentCaption($attachment)
    {
        if (!is_string($attachment['caption']) || $attachment['caption'] == '') {
            return '';
        }

        $html = '<div class="exhibit-item-caption item-meta">';
        if (isset($attachment['title'])) {
          $html .= '<h3>' . $attachment['title'] . '</h3>';
        }
        $html .= '<div class="item-description">'
              . $attachment['caption']
              . '</div></div>';
        if (isset($attachment['title'])) {
          $html .= '<div class="item-title">' . $attachment['title'] . '</div>';
        }

        return apply_filters('exhibit_attachment_caption', $html, array(
            'attachment' => $attachment
        ));
    }
}
