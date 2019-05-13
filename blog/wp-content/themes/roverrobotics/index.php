<?php get_header(); ?>

<div class="container">
  <div class="row">
    <div class="col-dk-8 col-dk-push-2">
      <h1>
        <span>Rover</span><br />
        <span>Blog</span>
      </h1>

      <div class="row row--posts">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <div class="col-mb-lg-6">
          <?php include(locate_template('partials/post-index.php')); ?>    
        </div>
        <?php endwhile; endif; ?>
      </div>
    </div>    
  </div>
</div>

<?php get_footer(); ?>
