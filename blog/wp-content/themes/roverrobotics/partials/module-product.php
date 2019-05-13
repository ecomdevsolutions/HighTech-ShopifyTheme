<div class="module module--post-product">
  <div class="product">
    <div class="row">
      <div class="col-tb-lg-6 col-dk-6">
        <div class="product__image">
          <?php $image = $module['image']; ?>  
          <div class="box" style="padding-top: <?php echo get_acf_image_padding_top($image); ?>">
            <div class="box__inner">
              <div class="image image--cover image--load--fadein">
                <img class="lazyload" data-src="<?php echo get_acf_image_url($image); ?>" />
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-tb-lg-6 col-dk-6">    
        <h5 class="h5"><?php echo $module['title']; ?></h5>  
        <p><?php echo $module['description']; ?></p>
        <a class="button button--primary" href="<?php echo $module['url']; ?>">
          <span>Details</span>
        </a>
      </div>
    </div>
  </div>
</div>
