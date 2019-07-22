


class PrivacyPolicy {
    constructor() {
        this.$popup = $('#privacy-popup');
        this.$cookieAccept = $('#cookie-accept')
        this.$cookieDecline = $('#cookie-decline')
        this.privacyCookiename = "privacyAccepted"
        this.privacyCountries = ['AT','BE','BG','CY','CZ','DE','DK','EE','ES','FI','FR','GB','GR','HR','HU','IE', 'IT','LT', 'LU','LV','MT','NL', 'PO','PT','RO', 'SE', 'SI','SK']
    }


    init() {
        const bannerCookie = this.getCookie(this.privacyCookiename)
        // if user is from us place cookies otherwise display popup
        if (bannerCookie === "none") {
            this.getRequestCountry()
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

    getRequestCountry(){
     $.getJSON("https://freegeoip.app/json/", (data) => {
         console.log(data.country_code)
        if (this.privacyCountries.includes(data.country_code)) {
            this.$popup.show()
            this.acceptListen()
            this.declineListen()
        } else if (data.country_code == "US") {
             this.setCookie(this.privacyCookiename, true)
             window.roverTracking = true
             window.dataLayer.push({'event': 'cookieAccepted'})

        }
      });
    }
}

export default PrivacyPolicy;