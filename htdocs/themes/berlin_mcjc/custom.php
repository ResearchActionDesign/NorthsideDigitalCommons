<?php

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
      return;
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
