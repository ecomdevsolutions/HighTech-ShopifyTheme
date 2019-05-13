import Application from '../shared/Application'

import Home from './pages/Home'
import Post from './pages/Post'

class Blog {
  constructor () {
    this.application = new Application([
      Home,
      Post
    ])
  }
}


new Blog
