import HeroCarousel from '../modules/HeroCarousel'
import FeaturedPosts from '../modules/FeaturedPosts'

class Home {
  constructor () {
    this.$heroCarousel = $('.module--hero-carousel')
    this.$featuredPosts = $('.module--featured-posts')
    this.$video = $('.hero__video')
    this.$heroContainer = $('.hero')
  }

  static get bodyClass () {
    return 'shop-index'
  }

  init () {
    this.heroCarousel = new HeroCarousel(this.$heroCarousel)
    this.heroCarousel.init()


    $('a[href*=\\#]').on('click', function(e){
        e.preventDefault();
        $('html, body').animate({
            scrollTop : $(this.hash).offset().top
        }, 500);
    });



    this.featuredPosts = new FeaturedPosts(this.$featuredPosts)
    this.featuredPosts.init()
    if (!this.canPlayVideo()) {
      let styles = {
        background: "url('https://cdn.shopify.com/s/files/1/0055/0433/5925/files/all_three_revA_1000x1000.png?v=1529706427')",
        height: "75vh",
        backgroundSize: "contain",
        backgroundRepeat:" no-repeat",
        backgroundPosition: "center",
      }
      this.$video.hide()
      this.$heroContainer.css(styles)
    }
  }
  // checks for video support
  canPlayVideo() {
    let video = document.createElement('video');
    let sup = video.canPlayType( 'video/mp4' );

    if (sup === "probably" || sup === "maybe") {
      return true;
    }
    return false
  }


  destroy () {
    this.heroCarousel.destroy()
    this.featuredPosts.destroy()
  }
}

export default Home
