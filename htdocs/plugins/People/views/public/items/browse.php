<?php

$browseByPerson = TRUE;
$pageTitle = __('Meet our Neighbors');
$curLetter = $vars['cur_letter'];
$validLetters = $vars['letters'];

echo head(array('title'=>$pageTitle,'bodyclass' => 'people browse'));
?>

<h1><?php echo $pageTitle;?></h1>

<div class="search-by-lastname">
    <span><?php echo __('SEARCH BY LAST NAME'); ?></span>
    <ul>
        <li<?php echo (!$curLetter ? ' class="active"' : '');?>><a href="/people">All</a></li>
        <?php foreach (range('A', 'Z') as $letter): ?>
        <?php if (in_array($letter, $validLetters)): ?>
                <li<?php echo ($curLetter == $letter ? ' class="active"' : '');?>>
                <a href="/people?firstLetter=<?php echo $letter?>"><?php echo $letter ?></a>
                </li>
        <?php else: ?>
                <li class="disabled">
                    <?php echo $letter ?>
                </li>
        <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>

<?php foreach (loop('items') as $item): ?>
    <div class="item record">
        <h2><?php echo people_get_link_to_item(metadata('item', array('Dublin Core', 'Title')), array('class'=>'permalink')); ?></h2>
        <div class="item-meta">
          <?php if (metadata('item', 'has files')): ?>
              <div class="item-img">
                  <div class="item-images"><?php echo mcjc_files_for_item(); ?></div>
              </div>
          <?php endif; ?>

          <?php if ($description = metadata('item', array('Dublin Core', 'Description'), array('snippet'=>250))): ?>
              <div class="item-description">
                <?php echo $description; ?>
              </div>
          <?php endif; ?>

          <?php if (metadata('item', 'has tags')): ?>
              <div class="tags"><p><strong><?php echo __('Tags'); ?>:</strong>
                  <?php echo tag_string('items'); ?></p>
              </div>
          <?php endif; ?>

          <?php fire_plugin_hook('public_items_browse_each', array('view' => $this, 'item' =>$item)); ?>

        </div><!-- end class="item-meta" -->
    </div><!-- end class="item entry" -->
<?php endforeach; ?>

<?php echo pagination_links(); ?>

<?php fire_plugin_hook('public_items_browse', array('items'=>$items, 'view' => $this)); ?>

<?php echo foot(); ?>
