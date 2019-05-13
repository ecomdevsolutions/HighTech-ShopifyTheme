import Promise from 'bluebird'
import Masonry from 'masonry-layout'
import fitvids from 'fitvids'

import SocialShare from '../../shared/components/SocialShare'

class Post {  
  constructor () {
    
  }

  static get bodyClass () {
    return 'single-post'
  }

  init () {
    fitvids('.fitvids')

    this.$grid = $('.row--related-posts')

    this.masonry = new Masonry(this.$grid[0], {
      transitionDuration: 0,
      horizontalOrder: true
    })

    this.socialShare = new SocialShare
    
    return Promise.all([
      this.socialShare.init()
    ])
  }

  destroy () {
    this.socialShare.destroy()
  }
}

export default Post
