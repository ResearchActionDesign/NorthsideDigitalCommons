<?php

/**
 * Generate a custom subtitle for the current record (as an oral history item),
 * including text about interviewer and interview date.
 *
 * @return string
 */
function oral_history_item_subtitle() {
  $item = get_current_record('item');
  $convertNameFormat = function ($s) {
    if (strpos($s,
        ',') !== FALSE
    ) {
      return trim(substr(strstr($s,
        ','),
        1)) . ' ' . trim(strstr($s,
        ',',
        TRUE));
    }
    else {
      return $s;
    }
  };
  $interviewers = array_map(function ($a) use ($convertNameFormat) {
    return $convertNameFormat($a->text);
  },
    $item->getElementTexts('Item Type Metadata',
      'Interviewer'));
  $date = $item->getElementTexts('Item Type Metadata',
    'Interview Date');
  $text = '';
  if (empty($interviewers)) {
    if (empty($date)) {
      return "";
    }
    else {
      return sprintf('Interviewed on %s',
        date_format(date_create($date[0]->text),
          'F j, Y'));
    }
  }
  elseif (empty($date)) {
    return sprintf('Interviewed by %s',
      implode(' and ',
        $interviewers));
  }
  else {
    return sprintf('Interviewed by %s on %s',
      implode(' and ',
        $interviewers),
      date_format(date_create($date[0]->text),
        'F j, Y'));
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
      $html .= get_view()->partial('items/featured-item.php', array('item' => $item));
      release_object($item);
    }
  } else {
    $html = '<p>' . __('No featured items are available.') . '</p>';
  }
  return $html;
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
function mcjc_files_for_item($type = 'item', $options = array(), $wrapperAttributes = array('class' => 'item-file'), $item = null)
{
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
  $itemArray = $itemTable->findBy(array(
    'collection' => $collection->id,
    'hasImage' => true,
    'sort_field' => 'featured',
    'sort_dir' => 'd'
  ));
  if ($itemArray) {
    $files = array();
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
function mcjc_file_markup($files, array $props = array(), $wrapperAttributes = array('class' => 'item-file'))
{
  if (!is_array($files)) {
    $files = array($files);
  }
  $output = '';

  $files = mcjc_sort_files($files);

  // Create file output.
  foreach ($files as $key=>$file) {
    // Don't create markup for PDFs unless this is an item show page, and
    // only create markup for the first file if this is Browse Collections.
    if (!isset($props['show']) && strpos($file->mime_type, 'pdf') !== FALSE
    || isset($props['browse_collections']) && $key != 0) {
      continue;
    }
    $output .= get_view()->mcjcFileMarkup($file, $props, $wrapperAttributes);
  }
  return $output;
}

/**
 * Sorts an array of files so PDFs appear last.
 *
 * @param array $files The initial array of files.
 * @return array The sorted array, with PDFs last.
 */
function mcjc_sort_files($files) {
  // Remove PDF files so we can append them to the end of the file list.
  $pdfs = array();
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

  return($files);
}