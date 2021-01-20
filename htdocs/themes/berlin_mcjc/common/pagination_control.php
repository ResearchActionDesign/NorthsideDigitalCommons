<?php
if ($this->pageCount > 1):
    $getParams = $_GET; ?>
  <nav class="pagination-nav" aria-label="<?php echo __('Pagination'); ?>">
      <?php if (isset($this->previous)): ?>
        <!-- Previous page link -->
        <div class="pagination_previous">
          <?php $getParams['page'] = $previous; ?>
          <a rel="prev"
             href="<?php echo html_escape(
                 $this->url([], null, $getParams)
             ); ?>"><?php echo __('<i class="fa fa-arrow-left" aria-hidden="true"></i>
             '); ?>Previous Page</a>
        </div>
      <?php endif; ?>
      <ul class="pagination">
      <?php foreach (
          range($firstPageInRange, $lastPageInRange)
          as $pageNumber
      ): ?>
            <li class="page-input<?php echo $current === $pageNumber
                ? 'active'
                : ''; ?>">
        <?php if (
            $first < $firstPageInRange &&
            $pageNumber === $firstPageInRange
        ): ?>
              ...
        <?php endif; ?>
              <?php $getParams['page'] = $pageNumber; ?>
                <a
                   href="<?php echo html_escape(
                       $this->url([], null, $getParams)
                   ); ?>"><?php echo $pageNumber; ?></a>
        <?php if (
            $last > $lastPageInRange &&
            $pageNumber === $lastPageInRange
        ): ?>
              ...
        <?php endif; ?>
            </li>
        <?php endforeach; ?>
      </ul>
      <?php if (isset($this->next)): ?>
        <!-- Next page link -->
        <div class="pagination_next">
          <?php $getParams['page'] = $next; ?>
          <a rel="next"
             href="<?php echo html_escape(
                 $this->url([], null, $getParams)
             ); ?>">Next Page <?php echo __('<i class="fa fa-arrow-right" aria-hidden="true"></i>
             '); ?></a>
        </div>
      <?php endif; ?>
  </nav>

<?php
endif; ?>
