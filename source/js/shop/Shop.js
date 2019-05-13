require('slick-carousel')

import Application from '../shared/Application'
import Home from './pages/Home'
import Product from './pages/Product'
import Contact from './pages/Contact'
import Login from './pages/Login'
import Addresses from './pages/Addresses'

class Shop {
  constructor () {            
    this.application = new Application([
      Home,
      Product,
      Contact,
      Login,
      Addresses
    ])    
  }
}

new Shop
