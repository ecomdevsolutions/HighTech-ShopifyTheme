require('application.scss')
require('jquery-pjax')
require('ext/lazysizes')


import Promise from 'bluebird'


import Header from './components/Header'

class Application {
  constructor ( pages ) {
    this._pages = pages || []

    this.$window = $(window)
    this.$document = $(document)
    this.$html = $('html')
    this.$body = $('body')


    this.onWindowLoad = this.onWindowLoad.bind(this)
    this.onWindowResize = this.onWindowResize.bind(this)
    this.onWindowOrientationchange = this.onWindowOrientationchange.bind(this)
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

      this.setHtmlClasses()
      this.init();
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

  onLazyloaded () {
    window.objectFitPolyfill()
  }

  onWindowLoad () {

  }

  onWindowResize ( e ) {
    clearTimeout(this.resizeTO)
    this.resizeTO = setTimeout(() => {
      this.$window.trigger('resizeend')
    }, 300)
  }


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
