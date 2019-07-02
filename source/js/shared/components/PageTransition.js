import Promise from 'bluebird'

class PageTransition {
  constructor () {
    this.$window = $(window)
    this.$el = $('.page-transition')
  }

  start () {
    return new Promise((resolve, reject) => {
      //this.$el.addClass('in')
      setTimeout(() => {
        resolve()
        this.isOpen = true
      }, 500)
    })
  }

  end () {
    this.endInterval = setInterval(() => {
      if ( !this.isOpen ) return
      clearInterval(this.endInterval)

      this.$el.addClass('out')

      setTimeout(() => {
        this.$el
            .removeClass('in')
            .removeClass('out')
        this.isOpen = false
      }, 1000)
    }, 10)
  }
}

export default PageTransition
