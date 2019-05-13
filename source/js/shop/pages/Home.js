import HeroCarousel from '../modules/HeroCarousel'
import FeaturedPosts from '../modules/FeaturedPosts'

class Home {  
  constructor () {
    this.$heroCarousel = $('.module--hero-carousel')
    this.$featuredPosts = $('.module--featured-posts')
  }

  static get bodyClass () {
    return 'shop-index'
  }

  init () {
    this.heroCarousel = new HeroCarousel(this.$heroCarousel)
    this.heroCarousel.init()

    this.featuredPosts = new FeaturedPosts(this.$featuredPosts)
    this.featuredPosts.init()
  }

  destroy () {
    this.heroCarousel.destroy()
    this.featuredPosts.destroy()    
  }
}

export default Home
