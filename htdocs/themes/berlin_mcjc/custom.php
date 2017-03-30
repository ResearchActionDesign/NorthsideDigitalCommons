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