<?php

/**
 * Generate a custom subtitle for the current record (as an oral history item),
 * including text about interviewer and interview date.
 *
 * @return string
 */
function oral_history_item_subtitle($item = false)
{
    if (!$item) {
        $item = get_current_record('item');
    }

    $convertNameFormat = function ($s) {
        if (strpos($s, ',') !== false) {
            return trim(substr(strstr($s, ','), 1)) .
                ' ' .
                trim(strstr($s, ',', true));
        } else {
            return $s;
        }
    };
    $interviewers = array_map(function ($a) use ($convertNameFormat) {
        return $convertNameFormat($a->text);
    }, $item->getElementTexts('Item Type Metadata', 'Interviewer'));
    $date = $item->getElementTexts('Item Type Metadata', 'Interview Date');
    $text = '';
    if (empty($interviewers)) {
        if (empty($date)) {
            return "";
        } else {
            return sprintf(
                'Interviewed on %s',
                date_format(date_create($date[0]->text), 'F j, Y')
            );
        }
    } elseif (empty($date)) {
        return sprintf('Interviewed by %s', implode(' and ', $interviewers));
    } else {
        return sprintf(
            'Interviewed by %s on %s',
            implode(' and ', $interviewers),
            date_format(date_create($date[0]->text), 'F j, Y')
        );
    }
}

/**
 * Render audio player for an oral history item.
 */
function mcjc_render_oral_history_players(
    $files = null,
    $item = null,
    $wrapperAttributes = ['class' => 'item-file'],
    $options = []
) {
    if (!$files) {
        $files = $item->Files;
    }
    if (!isset($options['title']) && $item) {
        $options['title'] = metadata($item, ['Dublin Core', 'Title']);
    }
    $limit = $options['limit'] ?? false;
    $output = "";
    $audioFiles = array_filter($files, function ($file) {
        return substr($file->mime_type, 0, 5) === 'audio';
    });
    $count = 1;
    foreach ($audioFiles as $audiofile) {
        $output .=
            '<div class="player-element">' .
            get_view()->mcjcFileMarkup(
                $audiofile,
                $options,
                $wrapperAttributes
            ) .
            '</div>';
        $count++;
        if ($limit && $count > $limit) {
            break;
        }
    }
    return $output;
}

/**
 * Get HTML for all files assigned to an item. Customized for MCJC Berlin Theme.
 *
 * @package Omeka\Function\View\Item
 * @uses file_markup()
 * @param array $options
 * @param array $wrapperAttributes
 * @param Item|null $item Check for this specific item record (current item if null).
 * @return string HTML
 */
function mcjc_files_for_item(
    $type = 'item',
    $options = [],
    $wrapperAttributes = ['class' => 'item-file'],
    $item = null
) {
    if (!$item) {
        $item = get_current_record($type);
    }
    $options['item'] = $item;

    if ($type == 'collection') {
        $files = mcjc_get_collection_files($item);
    } else {
        $files = $item->Files;
    }

    // If first file is an image, skip it since it will already have been displayed by item_image.
    if (count($files) && substr($files[0]->mime_type, 0, 5) === 'image') {
        array_shift($files);
    }

    return mcjc_file_markup($files, $item, $options, $wrapperAttributes);
}

/**
 * Get URL to the image, for use in social metadata.
 */
function mcjc_item_image_url($item = null)
{
    if (!$item) {
        $item = get_current_record('item', false);
    }
    if ($item) {
        $imageFile = $item->getFile($index);
        if ($imageFile) {
            return $imageFile->getWebPath('fullsize');
        }
    }
    return false;
}

/**
 * Get a customized item image tag, using Lity to display image.
 *
 * @package Omeka\Function\View\Item
 * @uses Omeka_View_Helper_FileMarkup::image_tag()
 * @param string $imageType Image size: thumbnail, square thumbnail, fullsize
 * @param array $props HTML attributes for the img tag
 * @param int $index Which file within the item to use, by order. Default
 *  is the first file.
 * @param Item|null Check for this specific item record (current item if null).
 */
function mcjc_item_image(
    $imageType = null,
    $props = [],
    $index = 0,
    $item = null
) {
    if (!$item) {
        $item = get_current_record('item');
    }
    $imageFile = $item->getFile($index);
    if (substr($imageFile->mime_type, 0, 5) !== 'image') {
        return false;
    }

    $source = metadata($imageFile, ['Dublin Core', 'Source']);
    $description = metadata($imageFile, ['Dublin Core', 'Description']);

    $fileMarkup = new Omeka_View_Helper_FileMarkup();
    $imageTag = $fileMarkup->image_tag($imageFile, $props, $imageType);
    $originalHref = $imageFile->getWebPath('fullsize');
    $returnHtml =
        '<a href="' . $originalHref . '" data-lity>' . $imageTag . '</a>';
    if ($description) {
        $returnHtml .= '<p class="image--description">' . $description . '</p>';
    }

    if ($source) {
        $returnHtml .= '<p class="image--source">' . $source . '</p>';
    }

    return $returnHtml;
}
/**
 * Get all files for this Collection.
 *
 * @param Collection $collection
 * @return array|null
 */
function mcjc_get_collection_files($collection)
{
    $itemTable = $collection->getDb()->getTable('Item');
    $itemArray = $itemTable->findBy([
        'collection' => $collection->id,
        'hasImage' => true,
        'sort_field' => 'featured',
        'sort_dir' => 'd',
    ]);
    if ($itemArray) {
        $files = [];
        foreach ($itemArray as $item) {
            $files[] = $item->getFile();
        }
        return $files;
    } else {
        return null;
    }
}

/**
 * Get HTML for a set of files. Customized for MCJC Berlin Theme.
 *
 * @uses MCJCDeployment_View_Helper_McjcFileMarkup::mcjcFileMarkup()
 * @param File $files A file record or an array of File records to display.
 * @param array $props Properties to customize display for different file types.
 * @param array $wrapperAttributes Attributes HTML attributes for the div that
 * wraps each displayed file. If empty or null, this will not wrap the displayed
 * file in a div.
 * @return string HTML
 */
function mcjc_file_markup(
    $files,
    $item = null,
    array $props = [],
    $wrapperAttributes = ['class' => 'item-file']
) {
    if (!is_array($files)) {
        $files = [$files];
    }
    $output = '';

    $files = mcjc_sort_files($files);

    // If this is a standard item, start with rendering the oral history player.
    $output .= mcjc_render_oral_history_players(
        array_filter($files, function ($file) {
            return substr($file->mime_type, 0, 5) === 'audio';
        }),
        $item
    );

    // Create file output for non-image, non-audio files.
    foreach (
        array_filter($files, function ($file) {
            return !in_array(substr($file->mime_type, 0, 5), [
                'audio',
                'image',
            ]);
        })
        as $key => $file
    ) {
        // Don't create markup for PDFs unless this is an item show page, and
        // only create markup for the first file if this is Browse Collections.
        if (
            (!isset($props['show']) &&
                strpos($file->mime_type, 'pdf') !== false) ||
            (isset($props['browse_collections']) && $key != 0)
        ) {
            continue;
        }
        $output .= get_view()->mcjcFileMarkup(
            $file,
            $props,
            $wrapperAttributes
        );
    }

    // Add all images as carousel.
    $images = array_filter($files, function ($file) {
        return substr($file->mime_type, 0, 5) === 'image';
    });
    if (count($images) > 1) {
        $output .= "<div class='item-images-slider'>";
    }
    foreach ($images as $image) {
        $output .= get_view()->mcjcFileMarkup(
            $image,
            $props,
            $wrapperAttributes
        );
    }
    if (count($images) > 1) {
        $output .= "</div>";
        $output .= common('slider-markup', ['class' => 'item-images-slider']);
    }

    return $output;
}

/**
 * Sorts an array of files so PDFs appear last.
 *
 * @param array $files The initial array of files.
 * @return array The sorted array, with PDFs last.
 */
function mcjc_sort_files($files)
{
    // Remove PDF files so we can append them to the end of the file list.
    $pdfs = [];
    foreach ($files as $key => $file) {
        if ($file->mime_type == 'application/pdf') {
            $pdfs[] = $file;
            unset($files[$key]);
        }
    }

    // Reset the array keys.
    $files = array_values($files);

    // Add PDF files to end of array of files.
    $files = array_merge($files, $pdfs);

    return $files;
}

function mcjc_get_linked_sohp_interview($item = false)
{
    if (!$item) {
        $item = get_current_record('item');
    }

    if (
        metadata($item, ['Dublin Core', 'Creator']) ===
        "Southern Oral History Program"
    ) {
        // TODO: Check if source is a valid link.
        $source = metadata('item', ['Dublin Core', 'Source']);
        if (substr($source, 0, 4) === "http") {
            return $source;
        }
    }
    return false;
}

/**
 * Return HTML rendering of the sub-menu for whatever branch of the main nav a given page is on.
 *
 * @see https://framework.zend.com/manual/2.4/en/modules/zend.navigation.view.helper.menu.html for more details on
 *   how to customize the behavior of the last line.
 *
 * @return mixed
 * @throws Zend_Navigation_Exception
 */
function mcjc_get_submenu()
{
    $view = get_view();
    $nav = new Omeka_Navigation();
    $nav->loadAsOption(Omeka_Navigation::PUBLIC_NAVIGATION_MAIN_OPTION_NAME);
    $nav->addPagesFromFilter(
        Omeka_Navigation::PUBLIC_NAVIGATION_MAIN_FILTER_NAME
    );
    return $view
        ->navigation()
        ->menu($nav)
        ->setMaxDepth(1)
        ->renderSubmenu();
    // @TODO: Handle Zend_Navigation_Exception
}

function mcjc_sort_tags_by_first_letter($tags = null)
{
    if (!$tags) {
        return [];
    }

    $output = [];

    foreach ($tags as $tag) {
        $firstLetter = ucfirst($tag->name)[0];
        if (!ctype_alpha($firstLetter)) {
            $firstLetter = 'Digits';
        }
        if (!isset($output[$firstLetter])) {
            $output[$firstLetter] = [];
        }
        $output[$firstLetter][] = $tag;
    }

    return $output;
}

/**
 * Create a tag list with alphabetical pager.
 *
 * Based on globals.php tag_cloud function.
 *
 * @param Omeka_Record_AbstractRecord|array $recordOrTags The record to retrieve
 * tags from, or the actual array of tags
 * @param string|null $link The URI to use in the link for each tag. If none
 * given, tags in the cloud will not be given links.
 * @param int $maxClasses
 * @param bool $tagNumber
 * @param string $tagNumberOrder
 * @return string HTML for the tag cloud
 */
function mcjc_tags_list(
    $recordOrTags = null,
    $tagNumber = false,
    $tagNumberOrder = null
) {
    if (!$recordOrTags) {
        $tags = [];
    } elseif (is_string($recordOrTags)) {
        $tags = get_current_record($recordOrTags)->Tags;
    } elseif ($recordOrTags instanceof Omeka_Record_AbstractRecord) {
        $tags = $recordOrTags->Tags;
    } else {
        $tags = $recordOrTags;
    }

    if (empty($tags)) {
        return '<p>' . __('No tags are available.') . '</p>';
    }
    $html = '<div class="tags__list">';
    $tagCount = count($tags);
    foreach ($tags as $index => $tag) {
        $html .= '<span class="tags__tag">';
        // TODO -- should be url(array('tag' => $tag['name']), 'tagShow') once that controller has been created.
        $html .= '<a href="/tags/' . rawurlencode($tag['name']) . '">';
        if (
            $tagNumber &&
            $tagNumberOrder == 'before' &&
            isset($tag['tagCount'])
        ) {
            $html .= ' <span class="count">' . $tag['tagCount'] . '</span> ';
        }
        $html .= html_escape($tag['name']);
        if (
            $tagNumber &&
            $tagNumberOrder == 'after' &&
            isset($tag['tagCount'])
        ) {
            $html .= ' <span class="count">' . $tag['tagCount'] . '</span> ';
        }
        $html .= '</a>';
        $html .= '</span>';
        if ($index !== $tagCount - 1) {
            $html .= ', ';
        }
    }
    $html .= '</div>';

    return $html;
}

function _mcjc_oral_history_metadata_paragraph($item)
{
    $metadata = [
        'interviewer' => metadata($item, ['Item Type Metadata', 'Interviewer']),
        'interviewee' => metadata($item, ['Item Type Metadata', 'Interviewee']),
        'location' => metadata($item, ['Item Type Metadata', 'Location']),
        'processor' => metadata($item, [
            'Item Type Metadata',
            'Interview Processor',
        ]),
        'date' => metadata($item, ['Item Type Metadata', 'Interview Date']),
        'publisher' => metadata($item, ['Dublin Core', 'Publisher']),
    ];

    if ($metadata['date']) {
        $metadata['date'] = date_format(
            date_create($metadata['date']),
            'F j, Y'
        );
    }

    $first_sentence_chunks = [
        'Oral history interview',
        $metadata['interviewee'] ? "of {$metadata['interviewee']}" : false,
        $metadata['interviewer']
            ? "conducted by {$metadata['interviewer']}"
            : false,
        $metadata['date'] ? "on {$metadata['date']}" : false,
        $metadata['location'] ? "at {$metadata['location']}" : false,
    ];

    $sentences = [
        implode(" ", array_filter($first_sentence_chunks)) . '.',
        $metadata['processor']
            ? "Processed by {$metadata['processor']}."
            : false,
    ];

    return implode(" ", array_filter($sentences));
}

function _mcjc_image_metadata_paragraph($item)
{
    $metadata = [
        'subject' => implode(
            ' and ',
            explode(
                "\r\n",
                strip_tags(metadata($item, ['Dublin Core', 'Subject']))
            )
        ),
        'creator' => metadata($item, ['Dublin Core', 'Creator']),
        'date' => metadata($item, ['Dublin Core', 'Date']),
        'publisher' => metadata($item, ['Dublin Core', 'Publisher']),
    ];

    if ($metadata['date']) {
        $metadata['date'] = date_format(
            date_create($metadata['date']),
            'F j, Y'
        );
    }

    $first_sentence_chunks = [];
    if ($metadata['creator'] || $metadata['subject'] || $metadata['date']) {
        $first_sentence_chunks = [
            $metadata['creator'] === 'Jim Wallace'
                ? 'Photograph by Jim Wallace'
                : 'Image',
            $metadata['subject'] ? "of {$metadata['subject']}" : false,
            $metadata['date'] ? "made on {$metadata['date']}" : false,
        ];
    }

    $sentences = [
        count($first_sentence_chunks)
            ? implode(" ", array_filter($first_sentence_chunks)) . '.'
            : false,
    ];

    return implode(" ", array_filter($sentences));
}

/**
 * Returns a human-readable paragraph of key element texts.
 */
function mcjc_element_metadata_paragraph($item)
{
    $item_type = metadata($item, ['Dublin Core', 'Type']);
    if ($item_type === 'Oral History') {
        return _mcjc_oral_history_metadata_paragraph($item);
    } elseif ($item_type === 'Still Image') {
        return _mcjc_image_metadata_paragraph($item);
    }

    // TODO for other item types if needed.
    return '';
}

/**
 * Get an image tag for a record.
 *
 * @throws InvalidArgumentException If an invalid record is passed.
 * @uses MCJCDeployment_View_Helper_McjcImageTag::mcjcImageTag()
 * @param Omeka_Record_AbstractRecord|string $record
 * @param string $imageType Image size: thumbnail, square thumbnail, fullsize
 * @param array $props HTML attributes for the img tag
 * @return string
 */
function mcjc_record_image($record, $imageType = null, $props = [])
{
    if (is_string($record)) {
        $record = get_current_record($record);
    }

    if (!($record instanceof Omeka_Record_AbstractRecord)) {
        throw new InvalidArgumentException(
            'An Omeka record must be passed to record_image.'
        );
    }
    return get_view()->mcjcImageTag($record, $props, $imageType);
}

/**
 * Return tags similar to a query string.
 *
 * @param string $query Text to search for in tags table.
 * @return array Array of matching tag names.
 */
function mcjc_get_matching_tags($query)
{
    $db = get_db();
    $bind = ['%' . $query . '%'];

    $sql = "
            SELECT tags.name
            FROM {$db->Tag} tags
            WHERE tags.name LIKE ?
        ";
    return $db->fetchAll($sql, $bind) ?? [];
}

/**
 * Get random 'Have you heard' items from those set up in theme options.
 */
function mcjc_get_have_you_heard()
{
    $itemIndex = rand(1, 5);
    $count = 5;
    $haveYouHeard = [];
    while ($count > 0) {
        $item = trim(get_theme_option("have_you_heard_$itemIndex"));
        if ($item) {
            $haveYouHeard[] = $item;
        }
        $itemIndex = $itemIndex != 5 ? $itemIndex + 1 : 1;
        $count--;
    }

    return $haveYouHeard;
}

/**
 * Custom version -- return a tag string given an Item, Exhibit, or a set of tags.
 */
function mcjc_tag_string(
    $recordOrTags = null,
    $link = 'items/browse',
    $delimiter = null
) {
    // Set the tag_delimiter option if no delimiter was passed.
    if (is_null($delimiter)) {
        $delimiter = get_option('tag_delimiter') . ' ';
    }

    if (!$recordOrTags) {
        $tags = [];
    } elseif (is_string($recordOrTags)) {
        $tags = get_current_record($recordOrTags)->Tags;
    } elseif ($recordOrTags instanceof Omeka_Record_AbstractRecord) {
        $tags = $recordOrTags->Tags;
    } else {
        $tags = $recordOrTags;
    }

    if (empty($tags)) {
        return '';
    }

    $tagStrings = [];
    foreach ($tags as $tag) {
        $name = $tag['name'];
        if (!$link) {
            $tagStrings[] = html_escape($name);
        } else {
            $tagStrings[] =
                '<a href="/tags/' .
                html_escape($name) .
                '" rel="tag">' .
                html_escape($name) .
                '</a>';
        }
    }
    return join(html_escape($delimiter), $tagStrings);
}
