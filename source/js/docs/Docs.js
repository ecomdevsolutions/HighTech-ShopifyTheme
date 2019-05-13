import Application from '../shared/Application'

import Article from './pages/Article'

class Docs {
  constructor () {            
    this.application = new Application([
      Article
    ])
  }  
}

new Docs
