


class PrivacyPolicy {
    constructor() {
        this.$popup = $('#privacy-popup');
        this.$cookieAccept = $('#cookie-accept')
        this.$cookieDecline = $('#cookie-decline')
        this.privacyCookiename = "roverPrivacy"
        this.trackingCookies = ['']
    }


    init() {
        const bannerCookie = this.getCookie(this.privacyCookiename)

        if (bannerCookie === "none") {
            this.$popup.show()
            this.acceptListen()
            this.declineListen()

        }
        else if (JSON.parse(bannerCookie) === true) {
           window.roverTracking = true
        } else if (JSON.parse(bannerCookie) === false) {
            window.roverTracking = false
        }

    }

    acceptListen() {
        this.$cookieAccept.click((e) => {
            this.setCookie(this.privacyCookiename, true)
            window.roverTracking = true
            window.dataLayer.push({'event': 'cookieAccepted'})


            this.$popup.hide()
        });
    }

    declineListen() {
        this.$cookieDecline.click((e) => {
            this.setCookie(this.privacyCookiename, false)
            window.roverTracking = false

            this.$popup.hide()
        })
    }

    setCookie(name, value) {
        return document.cookie = `${name}=${value};path=/`
    }

    getCookie(name) {
         var name = name + "=";
          var decodedCookie = decodeURIComponent(document.cookie);
          var ca = decodedCookie.split(';');
          for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
              c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
              return c.substring(name.length, c.length);
            }
          }
          return "none";
    }

    deleteCookie() {

    }
}


export default PrivacyPolicy;