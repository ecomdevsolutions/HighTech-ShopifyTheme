import Application from '../shared/Application'

class Forums {
  constructor () {
    $(this.onReady.bind(this))
    this.application = new Application
  }

  onReady () {
    $('body').addClass('forums')
  }
}

new Forums
