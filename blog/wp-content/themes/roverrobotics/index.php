<?php get_header(); ?>
<?php $categories = get_categories(); ?>
<div class="container">
  <div class="row">
    <div class="col-dk-8 col-dk-push-2">
      <h1>
        <span>Rover</span><br />
        <span>Blog</span>
      </h1>
     
      <ul class="tags">
        <?php $current_category = get_query_var('cat'); 
          $cat_id = get_query_var('cat');
           $tag_colors = array(
            "Company Update" => "tag--brown",
            "Tutorial" => "tag--orange",
            "Product Review" => "tag--red",
            "Customer Case Study" => "tag--blue",
            "White Paper" => "tag--white",
            "Uncategorized" => "tag--grey"
        )
        ?>
        <?php 

          if (!$current_category) { ?>
            <li class="tag--black">
              <a href="/">
                All            
              </a>
            </li>
              <?php 
              foreach($categories as $c) { ?>
              <li class="<?php echo $tag_colors[$c->name] ?>">
                <a href="<?php echo get_category_link($c->term_id) ?>">
                  <?php echo $c->name ?>
                </a>                
              </li>
          <?php 
            } 
          } else { ?>
            <li class="tag--grey">
              <a href="/">
                All            
              </a>
            </li>
            <?php 
            foreach($categories as $c) { 
              if ($current_category == $c->term_id) { ?>
                <li class="<?php echo $tag_colors[$c->name] ?>">
                  <a href="<?php echo get_category_link($c->term_id) ?>">
                    <?php echo $c->name ?>
                  </a>                
                </li>
              <?php 
              } else { ?>
                 <li class="tag--grey">
                  <a href="<?php echo get_category_link($c->term_id) ?>">
                    <?php echo $c->name ?>
                  </a>                
                </li>
                
              <?php } //end else
            
             
            } //end foreach
          } // end else

          ?>
       
      </ul>
      <hr/>
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
