class HeroCarousel {
  constructor ( $el ) {
    this.$el = $el
    this.$carousel = this.$el.find('.carousel')
    this.$slides = this.$el.find('.carousel__slide')
    this.$nav = this.$el.find('.nav--slick')
  }

  init () {
    if ( !this.$el.length ) return 
    
    this.onAfterChange = this.onAfterChange.bind(this)
    
    this.initialized = true
    
    this.initSlick()
  }

  initSlick () {
    this.$carousel.on('afterChange', this.onAfterChange)
    this.$carousel.slick({
      rows: 0,
      dots: true,
      appendDots: '.nav--slick__dots',
      prevArrow: '.nav--slick__prev',
      nextArrow: '.nav--slick__next',
      autoplay: true,
      autoplaySpeed: 5000
    })

    if ( this.$slides.length == 1 ) {
      this.$nav.remove()  
    }
  }

  destroy () {
    if ( this.initialized ) {
      this.initialized = false
    }    
  }

  onAfterChange () {    
    let $current = this.$el.find('.slick-active'),
        $prev = $current.prev(),
        $next = $current.next()

    let images = []
    
    if ( $prev.length ) {
      images.push($prev.find('img')[0])
    }
    
    if ( $next.length ) {
      images.push($next.find('img')[0])
    }

    images.forEach(image => {
      lazySizes.loader.unveil(image)      
    })
  }
}

export default HeroCarousel
