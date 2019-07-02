require('application.scss')
require('jquery-pjax')
require('ext/lazysizes')

import Modernizr from 'modernizr'
import objectFitPolyfill from 'objectFitPolyfill'
import ScrollReveal from 'scrollreveal'
import Promise from 'bluebird'

import { getHostName, isExternalLink } from '../utils/url'
import { inIframe } from '../utils/media'

import LoadingScreen from './components/LoadingScreen'
import PageTransition from './components/PageTransition'
import Header from './components/Header'

class Application {
  constructor ( pages ) {
    this._pages = pages || []

    this.$window = $(window)
    this.$document = $(document)
    this.$html = $('html')
    this.$body = $('body')

    this.onLinkClick = this.onLinkClick.bind(this)
    this.onWindowLoad = this.onWindowLoad.bind(this)
    this.onWindowResize = this.onWindowResize.bind(this)
    // this.onWindowScroll = this.onWindowScroll.bind(this)
    this.onWindowNavigate = this.onWindowNavigate.bind(this)
    this.onWindowOrientationchange = this.onWindowOrientationchange.bind(this)
    this.onPjaxPopstate = this.onPjaxPopstate.bind(this)
    this.onPjaxStart = this.onPjaxStart.bind(this)
    this.onPjaxSuccess = this.onPjaxSuccess.bind(this)
    this.onLazyloaded = this.onLazyloaded.bind(this)

    $(this.onReady.bind(this))

    this.$document
        .on('pjax:popstate', this.onPjaxPopstate)
        .on('pjax:start', this.onPjaxStart)
        .on('pjax:complete', this.onPjaxComplete)
        .on('pjax:success', this.onPjaxSuccess)
        .on('lazyloaded', this.onLazyloaded)

    this.$window
        .on('load', this.onWindowLoad)
        .on('resize', this.onWindowResize)
        .on('scroll', this.onWindowScroll)
        .on('navigate', this.onWindowNavigate)
        .on('orientationchange', this.onWindowOrientationchange)
  }

  onReady () {
    if ( $.pjax && $.pjax.defaults )
      $.pjax.defaults.maxCacheLength = 0

    //stops loading screen
    // this.$document.on(
    //   'click',
    //   'a:not([target="_blank"]):not([href^=mailto]):not([href^=tel]):not(.no-ajax)',
    //   this.onLinkClick
    // )

    this.setHtmlClasses()

    // this.loadingScreen = new LoadingScreen
    // this.loadingScreen.start()

    this.pageTransition = new PageTransition

    this.init().then(() => {
      //this.loadingScreen.end()
    })
  }

  init () {
    this.$body = $('body')

    let promises = []

    this.header = new Header
    promises.push(this.header.init())

    this.pages = []
    this._pages.forEach((P, i) => {
      if ( this.$body.hasClass(P.bodyClass) ) {
        let p = new P

        this.pages[i] = p
        promises.push(p.init())
      }
    })

    return Promise.all(promises)
  }

  destroy () {
    this.header.destroy()
    this.header = null

    this.pages.forEach(p => {
      p.destroy()
    })
  }

  gotoUrl ( url ) {
    if ( url.match(/[^\/]$/) )
      url += '/'

    this.pageTransition.start().then(() => {
      if ( inIframe() ) {
        window.location.href = url
      } else {
        $.pjax({
          url,
          container: '.ajax-content',
          fragment: '.ajax-content',
          timeout: 5000,
          maxCacheLength: 0
        })
      }
    })
  }

  setHtmlClasses () {
    if ( 'playsInline' in document.createElement('video') ) {
      this.$html.addClass('videoplaysinline')
    } else {
      this.$html.addClass('no-videoplaysinline')
    }
  }

  fixLinkTargets () {
    $('a[href^="http"]')
      .not('a[href^="http://'+$(location).attr('hostname')+'"]')
      .not('a[href^="https://'+$(location).attr('hostname')+'"]')
      .attr('target', '_blank')
  }

  onLinkClick ( e ) {
    if ( !this.$body.hasClass('forums') ) {
        const video =  $('hero__video')
        console.log(video)

        if (video.length > 0) {
          video.pause()
        }

    

      let $this = $(e.target),
          $a = $this.closest('a'),
          url = $a.attr('href')

      this.gotoUrl(url)

      e.preventDefault()
    }
  }

  onPjaxPopstate () {
    this.pageTransition.start()
  }

  onPjaxStart () {
    this.destroy()
  }

  onPjaxSuccess () {
    var klass = $('[itemprop="body-class"]').attr('content')

    this.$body.attr('class', klass)

    this.init().then(() => {
      this.pageTransition.end()
    })
  }

  onLazyloaded () {
    window.objectFitPolyfill()
  }

  onWindowLoad () {

  }

  onWindowNavigate ( e, data ) {
    this.gotoUrl(data.url)
  }

  onWindowResize ( e ) {
    clearTimeout(this.resizeTO)
    this.resizeTO = setTimeout(() => {
      this.$window.trigger('resizeend')
    }, 300)
  }

  // onWindowScroll ( e ) {
  //   let scrollTop = this.$window.scrollTop()
  //
  //   this.$body.toggleClass(
  //     'scrolling-up',
  //     scrollTop < this.lastScrollTop && scrollTop > 0
  //   )
  //
  //   this.$body.toggleClass(
  //     'scrolling-down',
  //     scrollTop > this.lastScrollTop && scrollTop > 0
  //   )
  //
  //   this.lastScrollTop = scrollTop
  //
  //   clearTimeout(this.scrollTO)
  //   this.scrollTO = setTimeout(() => {
  //     this.$window.trigger('scrollend')
  //   }, 300)
  // }

  onWindowMousewheel ( e ) {
    clearTimeout(this.mousewheelTO)
    this.mousewheelTO = setTimeout(() => {
      this.$window.trigger('mousewheelend')
    }, 300)
  }

  onWindowOrientationchange () {
    window.objectFitPolyfill()
  }
}

export default Application
