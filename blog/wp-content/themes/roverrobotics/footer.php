
    <?php include(locate_template('partials/get-in-touch.php')); ?>    
    <footer class="footer footer--main">        
        <div class="col-grid col-grid--lines">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>

        <div class="container">
          <div class="row">
            <div class="col-tb-10 col-tb-push-1 col-dk-8 col-dk-push-2">
              <div class="footer__super">
                <div class="footer__contact">
                  
                  <form class="form form--subscribe"
                        action="https://roverrobotics.us12.list-manage.com/subscribe/post?u=3ce45472fe4a667fefa934188&amp;id=7f6025208c" method="post" target="_blank">
                    <h6 class="h6--eyebrow">Sign up for the latest updates</h6>
                    <div class="form__group">
                      <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="Email">
                      <input type="hidden" name="b_3ce45472fe4a667fefa934188_7f6025208c" tabindex="-1" value="">
                      <button type="submit">
                        <span class="icon-arrow-large-right"></span>
                      </button>
                    </div>
                  </form>

                  <?php if ( ($locations = get_nav_menu_locations()) && isset($locations['social_menu']) ) : ?>
                  <?php $menu = get_term( $locations['social_menu'], 'nav_menu' ); ?>
                  <?php $menu_items = wp_get_nav_menu_items($menu->term_id); ?>
                  <nav class="nav nav--social">
                    <ul>
                    <?php foreach ( $menu_items as $menu_item ) : ?>
                      <li>
                        <a class="button button--circle button--circle--ko" href="<?php echo $menu_item->url; ?>" target="_blank">
                         <span class="<?php echo implode($menu_item->classes, ' '); ?>"></span>
                        </a>
                      </li>
                    <?php endforeach; ?>
                    </ul>
                  <?php endif; ?>                  
                </div>

                <nav class="nav nav--main">
                  <?php wp_nav_menu(array(
                    "theme_location" => "footer_menu",
                    "menu_class" => "",
                    "container" => false
                  ));?>                  
                </nav>
              </div>

              <div class="footer__sub">
                <nav class="nav nav--sub">
                  <?php wp_nav_menu(array(
                    "theme_location" => "footer_sub_menu",
                    "menu_class" => "",
                    "container" => false
                  ));?>                  
                </nav>
                <div class="copyright">©2018 Open Avatar Inc.</div>
              </div>
            </div>
          </div>
        </div>                    
      </footer>
    </main>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-118893562-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'UA-118893562-1');
    </script>
  </body>
</html>
