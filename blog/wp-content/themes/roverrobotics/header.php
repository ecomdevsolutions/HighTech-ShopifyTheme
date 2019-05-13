<!DOCTYPE html>
<html>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,user-scalable=no" />
    <meta name="description" content="<?php bloginfo('description'); ?>">

    <title><?php wp_title('-', true, 'right'); ?> <?php bloginfo('name'); ?></title>
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />    
    
    <?php wp_head(); ?>

    <script type="text/javascript">
      var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
    </script>
  </head>  

  <body <?php body_class(); ?>>
    <?php include(locate_template('partials/loading-screen.php')); ?>
    <?php include(locate_template('partials/page-transition.php')); ?>
    <?php include(locate_template('partials/col-grid.php')); ?>    
    <main class="main ajax-content" role="main">
      <meta itemprop="body-class" content="<?php echo implode(' ', get_body_class()); ?>">
      <header class="header header--main">
        <div class="col-grid col-grid--lines">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>
        
        <div class="logo">
          <a href="<?php echo get_field('shop_home', 'options'); ?>">
            <?php include(locate_template('partials/logo-combo.php')); ?>
          </a>
        </div>
        
        <button class="menu-toggle">
          <div class="menu-toggle__icon">
            <span></span>
            <span></span>
            <span></span>
          </div>
        </button>
        
        <nav class="nav nav--main">
          <div class="col-grid col-grid--fixed col-grid--block">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
          </div>
          
          <?php wp_nav_menu(array(
            "theme_location" => "main_menu",
            "menu_class" => "nav--main__main",
            "container" => false
          ));?>                    

          <ul class="nav--main__sub">
            <li><a href="<?php echo get_field('shop_home', 'options'); ?>/account">My Account</a></li>
            <li><a href="<?php echo get_field('shop_home', 'options'); ?>/cart">Cart</a></li>
          </ul>
          
          <?php if ( ($locations = get_nav_menu_locations()) && isset($locations['social_menu']) ) : ?>
          <?php $menu = get_term( $locations['social_menu'], 'nav_menu' ); ?>
          <?php $menu_items = wp_get_nav_menu_items($menu->term_id); ?>
          <h6 class="h6--eyebrow">
            <span>Follow Us</span>
          </h6>
          <ul class="nav--main__social">
            <?php foreach ( $menu_items as $menu_item ) : ?>
            <li>
              <a class="button button--circle button--circle--ko" href="<?php echo $menu_item->url; ?>">
                <span class="<?php echo implode($menu_item->classes, ' '); ?>"></span>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
        </nav>
      </header>
    
