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
 * Get HTML for random featured items. Customized for MCJC Berlin Theme.
 *
 * @package Omeka\Function\View\Item
 * @uses get_random_featured_items()
 * @param int $count Maximum number of items to show.
 * @param boolean $withImage Whether or not the featured items must have
 * images associated. If null, as default, all featured items can appear,
 * whether or not they have files. If true, only items with files will appear,
 * and if false, only items without files will appear.
 * @return string
 */
function mcjc_random_featured_items($count = 5, $hasImage = null)
{
    $items = get_random_featured_items($count, $hasImage);
    if ($items) {
        $html = '';
        foreach ($items as $item) {
            $html .= get_view()->partial('items/featured-item.php', [
                'item' => $item,
            ]);
            release_object($item);
        }
    } else {
        $html = '<p>' . __('No featured items are available.') . '</p>';
    }
    return $html;
}

/**
 * Render audio player for an oral history item.
 */
function mcjc_render_oral_history_players(
    $item,
    $wrapperAttributes = ['class' => 'item-file']
) {
    $files = $item->Files;
    $output = "";
    foreach (
        array_filter($files, function ($file) {
            return substr($file->mime_type, 0, 5) === 'audio';
        })
        as $audiofile
    ) {
        $output .= get_view()->mcjcFileMarkup(
            $audiofile,
            [],
            $wrapperAttributes
        );
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

    return mcjc_file_markup($files, $options, $wrapperAttributes);
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
 * @package Omeka\Function\View\File
 * @uses Omeka_View_Helper_FileMarkup::fileMarkup()
 * @param File $files A file record or an array of File records to display.
 * @param array $props Properties to customize display for different file types.
 * @param array $wrapperAttributes Attributes HTML attributes for the div that
 * wraps each displayed file. If empty or null, this will not wrap the displayed
 * file in a div.
 * @return string HTML
 */
function mcjc_file_markup(
    $files,
    array $props = [],
    $wrapperAttributes = ['class' => 'item-file']
) {
    if (!is_array($files)) {
        $files = [$files];
    }
    $output = '';

    $files = mcjc_sort_files($files);

    // Create file output.
    foreach ($files as $key => $file) {
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
 * Determines if a SimplePages page object is part of the `About` page hierarchy.
 * @param $page SimplePagesPage Page to check.
 */
function mcjc_is_about_page($page)
{
    if ($page->slug && $page->slug === 'about') {
        return true;
    } else {
        if ($page->parent_id) {
            $parent = get_db()
                ->getTable('SimplePagesPage')
                ->find($page->parent_id);
            return $parent && mcjc_is_about_page($parent);
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
            $firstLetter = '123';
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
        $html .= '<a href="/tags/' . utf8_htmlspecialchars($tag['name']) . '">';
        if ($tagNumber && $tagNumberOrder == 'before') {
            $html .= ' <span class="count">' . $tag['tagCount'] . '</span> ';
        }
        $html .= html_escape($tag['name']);
        if ($tagNumber && $tagNumberOrder == 'after') {
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

// TODO: Pull these dynamically and store in plugin variables as part of install process.
const ORAL_HISTORY_ITEM_TYPE = 4;
const PERSON_ITEM_TYPE = 12;
const IMAGE_ITEM_TYPE = 6;

/**
 * Overrides link_to_item() to insert custom routes for people, stories and images.
 *
 * @uses link_to_item()
 * @param string $text HTML for the text of the link.
 * @param Item $item Used for dependency injection testing or to use this function
 * @param array $props Properties for the <a> tag.
 * @param string $action The page to link to (this will be the 'show' page almost always
 * within the public theme).
 * outside the context of a loop.
 * @return string HTML
 */
function mcjc_link_to_item(
    $text = null,
    $item = null,
    $props = ['class' => 'item-link'],
    $action = 'show'
) {
    if (!$item) {
        $item = get_current_record('item');
    }

    $routesForItemType = [
        ORAL_HISTORY_ITEM_TYPE => 'stories',
        PERSON_ITEM_TYPE => 'people',
        IMAGE_ITEM_TYPE => 'images',
    ];

    if (array_key_exists($item->item_type_id, $routesForItemType)) {
        $permalink = metadata($item, ['Dublin Core', 'Permalink']);
        $url = url(
            ['permalink' => $permalink],
            $routesForItemType[$item->item_type_id] . ucfirst($action)
        );
        $attr = !empty($props) ? ' ' . tag_attributes($props) : '';
        return "<a href='{$url}'{$attr}>{$text}</a>";
    }

    return link_to_item($text, $props, $action, $item);
}

/**
 * Returns a human-readable paragraph of key element texts.
 */
function mcjc_element_metadata_paragraph($item)
{
    //  $texts = array(
    //    'subject' => metadata($item, array('Dublin Core', 'Subject')),
    //    'type' => metadata($item, array('Dublin Core', 'Type')),
    //    'coverage' => metadata($item, array('Dublin Core', 'Coverage')),
    //    'creator' => metadata($item, array('Dublin Core', 'Creator')),
    //    'date' => metadata($item, array('Dublin Core', 'Date')),
    //    'identifier' => metadata($item, array('Dublin Core', 'Identifier')),
    //    'format' => metadata($item, array('Dublin Core', 'Format')),
    //  );

    // TODO.
    return 'STUB ELEMENT PARAGRAPH DESCRIPTION';
}
