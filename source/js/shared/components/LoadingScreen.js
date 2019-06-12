import { TweenLite, Power2 } from 'gsap'
import Promise from 'bluebird'

import { isTablet } from 'utils/media'

class LoadingScreen {
  constructor () {
    this.$window = $(window)
    this.$el = $('.loading-screen')
  }

  start () {
    setTimeout(() => {
      this.canEnd = true
    }, 500)
  }

  end () {
    this.interval = setInterval(() => {
      if ( !this.canEnd ) return

      clearInterval(this.interval)
      this.$el.addClass('out')

      setTimeout(() => {
        this.$el.remove()
      }, 1200)
    }, 10)
  }
}

export default LoadingScreen
