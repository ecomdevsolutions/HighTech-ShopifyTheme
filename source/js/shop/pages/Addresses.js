class Addresses {
  static get bodyClass () {
    return 'shop-customers/addresses'
  }
  
  constructor () {
    this.$body = $('body')
    this.$newAddressButton = $('.address-new-toggle')
    this.$editAddressButton = $('.address-edit-toggle')
    this.$deleteAddressButton = $('.address-delete')
    this.$newAddressWrapper = $('#AddressNewForm')

    this.onNewAddressClick = this.onNewAddressClick.bind(this)
    this.onEditAddressClick = this.onEditAddressClick.bind(this)
    this.onDeleteAddressClick = this.onDeleteAddressClick.bind(this)
  }

  init () {
    this.initCountryProvinceSelectors()

    this.$newAddressButton.on('click', this.onNewAddressClick)
    this.$editAddressButton.on('click', this.onEditAddressClick)
    this.$deleteAddressButton.on('click', this.onDeleteAddressClick)
  }

  initCountryProvinceSelectors () {
    if ( $('#AddressProvinceContainerNew').length ) {
      new Shopify.CountryProvinceSelector('AddressCountryNew', 'AddressProvinceNew', {
        hideElement: 'AddressProvinceContainerNew'
      })
    }
    
    $('.address-country-option').each(function() {
      var formId = $(this).data('form-id')      
      
      var countrySelector = 'AddressCountry_' + formId
      var provinceSelector = 'AddressProvince_' + formId
      var containerSelector = 'AddressProvinceContainer_' + formId

      new Shopify.CountryProvinceSelector(countrySelector, provinceSelector, {
        hideElement: containerSelector
      })
    })
  }

  destroy () {}

  onNewAddressClick () {
    this.$newAddressWrapper.toggle(0)
  }

  onEditAddressClick ( e ) {
    var formId = $(e.currentTarget).data('form-id')
    $('#EditAddress_' + formId).toggle(0)
  }

  onDeleteAddressClick ( e ) {
    var $this = $(e.currentTarget),
        formId = $this.data('form-id'),
        confirmMessage = $this.data('confirm-message')
    
    if (confirm(confirmMessage || "Are you sure you wish to delete this address?")) {
      Shopify.postLink('/account/addresses/' + formId, {
        'parameters': {
          '_method': 'delete'
        }
      })
    }
  }
}

export default Addresses
