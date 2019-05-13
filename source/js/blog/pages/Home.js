import Masonry from 'masonry-layout'

class Home {

  constructor () {
    
  }

  static get bodyClass () {
    return 'blog'
  }

  init () {
    this.$grid = $('.row--posts')

    this.masonry = new Masonry(this.$grid[0], {
      transitionDuration: 0,
      horizontalOrder: true
    })
  }

  destroy () {

  }
  
}

export default Home
