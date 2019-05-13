<article class="post post--index">
  <div class="box" style="padding-top: <?php echo get_post_thumbnail_padding_top($post->ID); ?>;">
    <div class="box__inner">
      <div class="image image--cover image--load--fadein">
        <a href="<?php the_permalink(); ?>">
          <img class="lazyload" data-src="<?php echo get_post_thumbnail_url($post->ID); ?>" />
        </a>
      </div>
    </div>
  </div>
  <div class="post__meta">
    <time class="h6--eyebrow"><?php echo get_the_date('F j, Y', $post->ID); ?></time>
    <h2 class="h5"><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>
    <p><?php echo get_the_excerpt(); ?></p>
  </div>
</article>
