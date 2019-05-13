import qs from 'qs'
import {
  capitalize
} from '../../utils/string'

class SocialShare {
  constructor () {
    this.$body = $('body')
    this.$socialShare = $('.social-share')

    this.onSocialShareClick = this.onSocialShareClick.bind(this)
  }

  init () {
    this.$socialShare.find('[data-share]').on('click', this.onSocialShareClick)
  }

  destroy () {
    this.$socialShare.find('[data-share]').off('click', this.onSocialShareClick)
  }

  onSocialShareClick ( e ) {
    let $this     = $(e.currentTarget),
        platform  = capitalize($this.data('share')),
        windowUrl = this['getShare' + platform].call($this[0]), 
        width     = 700,
        height    = 583, 
        top       = window.screen.height / 2 - height / 2, 
        left      = window.screen.width / 2 - width / 2;
    
    let windowOpts = [
      'toolbar=no', 
      'location=no', 
      'status=no', 
      'menubar=no', 
      'scrollbars=yes', 
      'resizable=yes', 
      'width=' + width,
      'height=' + height, 
      'top=' + top, 
      'left=' + left
    ].join(',') 
    
    window.open(windowUrl, '_blank', windowOpts)    
  }

  getShareFacebook () {
    let $this    = $(this),
        endpoint = 'https://www.facebook.com/dialog/share?'

    let vars = {
      app_id: $this.data('app-id'),
      href: $this.data('uri'),
      display: 'popup',
      redirect_uri: $this.data('redirect-uri') + '?close=1'
    }

    return endpoint + qs.stringify(vars)
  }

  getShareTwitter () {
    let $this    = $(this),
        endpoint = 'https://twitter.com/intent/tweet?'

    let vars = {
      text: $this.data('text'),
      url: $this.data('url')
    }

    if ( $this.data('hashtags') )
      vars['hashtags'] = $this.data('hashtags')

    return endpoint + qs.stringify(vars)
  }

  getShareLinkedin () {
    let $this = $(this),
        endpoint = 'https://www.linkedin.com/shareArticle?'

    let vars = {
      mini: true,
      url: $this.data('url'),
      title: $this.data('title'),
      summary: $this.data('summary'),
      source: $this.data('source')
    }

    return endpoint + qs.stringify(vars)
  }
}

export default SocialShare
