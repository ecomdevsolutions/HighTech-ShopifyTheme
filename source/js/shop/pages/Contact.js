import loadGoogleMapsApi from 'load-google-maps-api'
import googleMapsStyles from '../../utils/googleMapsStyles'

class Contact {
  
  constructor () {
    this.onReady = this.onReady.bind(this)
    this.onError = this.onError.bind(this)
    this.$contactForm = $('#contact-form')
    this.$contactFormContainer = $('#contact-form-container')
    this.detectSubmit()

  }

  static get bodyClass () {
    return 'shop-page-contact'
  }

  init () {
    this.$el = $('#map')
    
    return loadGoogleMapsApi({
      key: this.apiKey
    }).then(this.onReady)
      .catch(this.onError)
  }

  destroy () {
    console.log('destroy')
  }

  get apiKey () {    
    return window.theme.apiKeys.googleMaps
  }

  get markerIcon () {
    return 'https://cdn.shopify.com/s/files/1/0019/5100/6835/files/icon-map-marker.png?9945941158907407736'
  }

  detectSubmit() {
     let urlParams = new URLSearchParams(window.location.search);
      if (urlParams.has('submitted')) {
        console.log(this)
        this.$contactForm.hide()
        this.$contactFormContainer.append(
            `<h1>Success!</h1>`
        )
      }
  }


  onReady ( googleMaps ) {
    let geocoder = new googleMaps.Geocoder(),
        address = this.$el.data('address')
    
    this.map = new googleMaps.Map(this.$el[0], {
      center: {
        lat: 40.7664227,
        lng: -73.9710422
      },
      zoom: 14,
      maxZoom: 17,
      styles: googleMapsStyles,
      streetViewControl: false,
      mapTypeControl: false,
      fullscreenControl: false,
      zoomControlOptions: {
        position: googleMaps.ControlPosition.RIGHT_CENTER
      }
    })
    
    geocoder.geocode({ address }, (results, status) => {
      if ( status == 'OK' ) {
        this.map.setCenter(results[0].geometry.location)
        
        let marker = new google.maps.Marker({
          map: this.map,
          icon: {
            url: this.markerIcon,
            scaledSize: new googleMaps.Size(24, 38)
          },
          position: results[0].geometry.location
        })
      } else {
        console.warn(status)
      }      
    })
  }

  onError ( error ) {
    console.log(error)
  }
}

export default Contact
