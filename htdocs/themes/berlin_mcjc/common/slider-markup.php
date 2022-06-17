<ul class="slider-controls" id="slider-controls" aria-label="Carousel Navigation" tabindex="0">
    <li class="prev" aria-controls="customize" tabindex="-1" data-controls="prev" aria-label="Previous slide">
        <i class="fa fa-arrow-circle-left"></i>
    </li>
    <li class="next" aria-controls="customize" tabindex="-1" data-controls="next" aria-label="Next slide">
        <i class="fa fa-arrow-circle-right"></i>
    </li>
</ul>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.3/tiny-slider.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/min/tiny-slider.js"></script>
<script type="module">
    var slider = tns({
        container: '.<?php echo $class; ?>',
        items: 1,
        controlsContainer: '#slider-controls',
    <?php if ($class === 'have-you-heard__container'): ?>
        nav: false,
        gutter: 16,
        responsive: {
  "768": {
    edgePadding: 140,
            }
        }
        <?php elseif ($class === 'item-images-slider'): ?>
        autoHeight: false,
        navPosition: 'bottom',
        lazyLoad: true,
        <?php endif; ?>
    });
</script>
