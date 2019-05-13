class Header {
  constructor () {
    this.$body = $('body')
    this.$el = $('.header--main')
    this.$menuToggle = this.$el.find('.menu-toggle')

    this.onMenuToggleClick = this.onMenuToggleClick.bind(this)
  }
  
  init () {
    this.$menuToggle.on('click', this.onMenuToggleClick)
  }

  destroy () {
    
  }

  onMenuToggleClick () {
    this.$body.toggleClass('menu-open')
  }
}

export default Header
