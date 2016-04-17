<?php

/**
 * Exhibit attachment view helper.
 * 
 * @package ExhibitBuilder\View\Helper
 */
class ExhibitBuilder_View_Helper_ExhibitAttachment extends Zend_View_Helper_Abstract
{
    /**
     * Return the markup for displaying an exhibit attachment.
     *
     * @param ExhibitBlockAttachment $attachment
     * @param array $fileOptions Array of options for file_markup
     * @param array $linkProps Array of options for exhibit_builder_link_to_exhibit_item
     * @param boolean $forceImage Whether to display the attachment as an image
     *  always Defaults to false.
     * @return string
     */
    public function exhibitAttachment($attachment, $fileOptions = array(), $linkProps = array(), $forceImage = false)
    {
        $item = $attachment->getItem();
        $file = $attachment->getFile();
        static $html5_audio_types;
        static $html5_video_types;
        static $size_constraint;

        if (class_exists('Html5MediaPlugin')) {
            if (!isset($html5_audio_types) || !isset($html5_video_types)) {
                $html5_settings = unserialize(get_option('html5_media_settings'));
                $html5_audio_types = $html5_settings['audio']['types'];
                $html5_video_types = $html5_settings['video']['types'];
            }

            if (!isset($size_constraint)) {
                $size_constraint = get_option('square_thumbnail_constraint');
            }
        }

        if ($file) {
            // HTML 5 media hook
            if (class_exists('Html5MediaPlugin')) {
                if (in_array($file->mime_type, $html5_audio_types)) {
                    $html = Html5MediaPlugin::audio($file, array(
                      'height' => $size_constraint,
                      'width' => $size_constraint
                    ));
                }
                elseif (in_array($file->mime_type, $html5_video_types)) {
                    $html = Html5MediaPlugin::video($file, array(
                      'height' => $size_constraint,
                      'width' => $size_constraint
                    ));
                }
            }
            if (!isset($html)) {
                if (!isset($fileOptions['imgAttributes']['alt'])) {
                    $fileOptions['imgAttributes']['alt'] = metadata($item, array(
                      'Dublin Core',
                      'Title'
                    ));
                }

                if ($forceImage) {
                    $imageSize = isset($fileOptions['imageSize'])
                      ? $fileOptions['imageSize']
                      : 'square_thumbnail';
                    $image = file_image($imageSize, $fileOptions['imgAttributes'], $file);
                    $html = exhibit_builder_link_to_exhibit_item($image, $linkProps, $item);
                }
                else {
                    if (!isset($fileOptions['linkAttributes']['href'])) {
                        $fileOptions['linkAttributes']['href'] = exhibit_builder_exhibit_item_uri($item);
                    }
                    $html = file_markup($file, $fileOptions, NULL);
                }
            }
        } else if($item) {
            $html = exhibit_builder_link_to_exhibit_item(null, $linkProps, $item);
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
