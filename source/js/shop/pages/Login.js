class Login {
  static get bodyClass () {
    return 'shop-customers/login'
  }
  
  constructor () {
    this.$body = $('body')
    this.$reset = $('a.reset')
    this.$cancel = $('a.cancel')

    this.onResetClick = this.onResetClick.bind(this)
    this.onCancelClick = this.onCancelClick.bind(this)
  }

  init () {
    this.$reset.on('click', this.onResetClick)
    this.$cancel.on('click', this.onCancelClick)
  }

  destroy () {

  }

  onResetClick () {
    this.$body.addClass('reset-password')
  }
  
  onCancelClick () {
    this.$body.removeClass('reset-password')
  }
}

export default Login
