<div class="breadcrumbs">
  <a href="/"><i class="fa fa-home" aria-label="Home"></i></a> &gt;
    <?php foreach ($trail as $name => $url): ?>
    <?php if ($url && $name !== 0): ?>
        <a href="<?php echo $url; ?>"><?php echo $name; ?></a> &gt;
    <?php else: ?>
        <span class="current"><?php echo $name === 0 ? $url : $name; ?></span>
    <?php endif; ?>
    <?php endforeach; ?>
</div>
