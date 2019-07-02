<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<div class="container">
  <div class="row">
    <div class="col-dk-6 col-dk-push-3">
      <article class="post post--single">
        <div class="post__meta">
          <time class="h6--eyebrow"><?php echo get_the_date('F j, Y', $post->ID); ?></time>
          <h1 class="h3"><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h1>
        </div>
        <div class="post__modules">
          <?php the_content(); ?>
          <?php $modules = get_field('modules'); ?>
          <?php if ( $modules ) : foreach ( $modules as $module ) :  ?>
          <?php include(locate_template('partials/module-' . $module['acf_fc_layout'] . '.php')); ?>
          <?php endforeach; endif;?>
        </div>

        <div class="post__share post__share--footer social-share">
          <h6 class="h6--eyebrow">Share</h6>
          <ul>
            <?php $wpsso = get_option('wpsso_options'); ?>
            <li>
              <a class="button button--circle no-ajax"
                 <?php if ( $wpsso ) : ?>data-app-id="<?php echo $wpsso['fb_app_id']; ?>"<?php endif; ?>
                 data-uri="<?php the_permalink(); ?>"
                 data-redirect-uri="<?php the_permalink(); ?>"
                 data-share="facebook">
                <span class="icon-facebook"></span>
              </a>
            </li>
            <li>
              <a class="button button--circle no-ajax"
                 data-text="From Rover Robotics: <?php the_title(); ?>"
                 data-url="<?php the_permalink(); ?>"
                 data-share="twitter">
                <span class="icon-twitter"></span>
              </a>
            </li>
            <li>
              <a class="button button--circle no-ajax"
                 data-url="<?php the_permalink(); ?>"
                 data-title="<?php the_title(); ?>"
                 data-summary="<?php echo get_the_excerpt(); ?>"
                 data-share="linkedin">
                <span class="icon-linkedin"></span>
              </a>
            </li>
          </ul>
        </div>
      </article>
    </div>
  </div>

  <div class="module module--related-posts">
    <div class="row">
      <div class="col-dk-8 col-dk-push-2">
        <?php $tags = wp_get_post_terms(get_queried_object_id(), 'post_tag', ['fields' => 'ids']); ?>
        <?php $args = array(
          'post__not_in' => array(get_queried_object_id()),
          'posts_per_page' => 4,
          'ignore_sticky_posts' => 1,
          'orderby' => 'rand',
          'tax_query' => array(
            array(
              'taxonomy' => 'post_tag',
              'terms'    => $tags
            )
          )
        ); ?>

        <?php $related = new wp_query( $args ); ?>
        <?php if ( $related->have_posts() ) : ?>
        <h2 class="module__title">Related Articles</h2>
        <div class="row row--related-posts">
          <?php while ( $related->have_posts() ) : $related->the_post(); ?>
          <div class="col-mb-lg-6">
            <?php include(locate_template('partials/post-index.php')); ?>
          </div>
          <?php endwhile; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>


<?php endwhile; endif; ?>

<?php get_footer(); ?>
