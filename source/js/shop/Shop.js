require('slick-carousel')

import Application from '../shared/Application'
import Home from './pages/Home'
import Product from './pages/Product'
import Contact from './pages/Contact'
import Login from './pages/Login'
import Addresses from './pages/Addresses'
import Comparison from './pages/Comparison';
import QuoteRequest from './pages/QuoteRequest';
import MobileDropDown from "./modules/MobileDropDown";

class Shop {
  constructor () {            
    this.application = new Application([
      Home,
      Product,
      Contact,
      Login,
      Addresses,
      Comparison,
      QuoteRequest
    ])

    this.mobileDropdown = new MobileDropDown();
  }
}

new Shop
