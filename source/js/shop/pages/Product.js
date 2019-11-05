import ImageMagnify from "../modules/ImageMagnify"

class Product {

  constructor () {
    this.$window = $(window)
    this.$body = $('body')
    this.$form = $('.shopify-product-form')
    this.$images = $('.product__images')
    this.$thumb = $('.product__thumb')
    this.$banner = $('.product-banner')

    this.$varientQuantity = $('.product__qty')
    this.$optionQuanity = $('.addon__qty')
    this.$variantSelect = this.$form.find('select[name="id"]')
    this.$optionValue = $('.product__option__value')

    this.$options = $('.tablinks--options')
    this.$varients = $('.tablinks--varients')
    this.$bannerPrice = $('.product-banner__price')
    this.$addons = $('.button--addon')
    this.$addToCart = $(".button--add-to-cart")
    this.currentVarient = null
    //total price in bottom banner
    this.price = null
    //array of objects off addons
    this.addons = []
    this.options = {}
    this.cartClicked = false
  }

  static get bodyClass () {
    return 'shop-product'
  }


  init () {
    this.initImages()
    this.filterImages(this.getSelectedVariant())

    if (product.options.length < 2) {
      this.currentVarient = CURRENT_VARIENT_PRICE
    }



    this.optionSelect()
    this.setAddonQuantity()
    this.addToCart()
    this.varientSelect()
    this.setVariantQuantity()
    this.changeImageByClick()
    this.updateQuantity()
    this.setDefaultAddon()

    window.onload = function() {
      const multiOptions = $('.multi-option').toArray();
      if (multiOptions.length) {
        multiOptions.forEach(option => option.click());
      }
      const largeImages = $('.img-magnifier-container img')
      console.log("magnifying images")
      largeImages.toArray().forEach((image) => {
        new ImageMagnify(image,3)
      })
    }


  }

  initImages () {
    this.$images.slick({
      rows: 0,
      dots: true,
      speed: 250,
      fade: true,
      appendDots: '.nav--slick__dots',
      prevArrow: '.nav--slick__prev',
      nextArrow: '.nav--slick__next'
    })

  }

  setDefaultAddon() {

    const $active = $('.tablinks.active')
    if (!$active[0]) {
      return
    }
    const activeVariant = $active[0].dataset.addon
    const $varientPlus = document.getElementById(`quantity-plus-${activeVariant}`);
    const $variantInput = document.getElementById(`quantity-${activeVariant}`);
    this.addons = []

    $(".addon__qty")
        .toArray()
        .forEach(addon => addon.value = 0)
    $('.product__option').toArray()
        .forEach(option => $(option).removeClass("addon__selected"))
    $varientPlus.click();


  }

  changeImageByClick() {
    this.$thumb.click((e) => {
      e.stopPropagation();
      this.$images.slick('slickGoTo', parseInt(e.target.dataset.index))


      //new ImageMagnify($('.img-magnifier-container img')[0],3)
    })
  }

  filterImages ( variant ) {
    this.$images.slick('slickUnfilter')
    this.$thumb.removeClass('active')

    let filter = (k,v) => {
      let $this = $(v),
          variants = $this.data('variants')

      if ( variants.length == 0 ||
           variants.indexOf(variant.id) > -1 ) {
        return v
      }
    }

    this.$images.slick('slickFilter', filter)
    this.$thumb.filter(filter).addClass('active')
  }

  updateVariant () {
    let variant = this.getSelectedVariant();
    this.$bannerPrice.html(Shopify.formatMoney(variant.price).replace(/(\..*)/, ''))
    this.$variantSelect.val(variant.id)
    this.filterImages(variant)
  }

  updateQuantity() {
    $('.plus, .minus').click((e) => {
      const field = $(`#quantity-${e.target.dataset.id}`)
      const fieldValue = parseInt(field.val())
      if (e.target.value == "-") {
        const decrement = fieldValue - 1
        field.val(decrement)
      } else {
        const increment = fieldValue + 1
        field.val(increment)
      }
      field.change()

      if (parseInt(field.val()) > 0) {

        $(`#product__option-${e.target.dataset.id}`).addClass('addon__selected')
      } else {
        $(`#product__option-${e.target.dataset.id}`).removeClass('addon__selected')

      }
    })
  }

  getSelectedVariant () {
    let $selected = this.$optionValue.filter('.selected'),
        selected = {},
        variant
    $selected.each((k,v) => {
      let $this = $(v),
          position = $this.data('option-position'),
          value = $this.data('option-value')

      selected[`option${position}`] = value
    })

    window.product.variants.forEach(v => {
      if ( v.option1 == selected.option1 &&
           v.option2 == selected.option2 &&
           v.option3 == selected.option3 ) {
        variant = v
      }
    })

    return variant
  }

  destroy() {

  }

  //selects varent via tabs in upper right
  optionSelect() {
    this.$options.click((e) => {
      console.log("selecting options")
      let button = $(e.target)
      this.options[e.target.dataset.name] = e.target.dataset.value
      console.log(this.options, "options")
      let values = Object.values(this.options)
      for (let option of this.$options) {
        if (values.includes(option.dataset.value)) {
          $(option).css("background-color", "#0074E4")
          $(option).css("color", "#fff")
        } else {
          $(option).css("background-color", "#fff")
          $(option).css("color", "#000")
        }
      }
      if (values.length === product.options.length) {
        let variant = this.findVarient()
        this.currentVarient = {id: variant[0].id, price: variant[0].price, quantity: 1}
        $('.product-banner__price').text(Shopify.formatMoney(variant[0].price).replace(/(\..*)/, ''))
      }
    });
  }

  findVarient() {
      const values = Object.values(this.options);
      return product.variants.filter((variant) => {
        if(values.length === 3) {
          if (values.includes(variant.option1) && values.includes(variant.option2) && values.includes(variant.option3)) {
            return variant
          }
        }
        if (values.length === 2) {
           if (values.includes(variant.option1) && values.includes(variant.option2)) {
            return variant
          }
        }

      });
  }

  varientSelect() {
    this.$varients.click((e) => {
      if (e.target.className.split(" ").includes("tablinks")) {
        this.currentVarient = {id: e.target.dataset.id, price: parseFloat(e.target.dataset.price), quantity: 1}
        this.updatePrice()
        this.setDefaultAddon()
      }

    });
  }

  setVariantQuantity() {
    this.$varientQuantity.change((e) => {
      let quantity = parseFloat(e.target.value)
      this.currentVarient.quantity = quantity
      this.updatePrice()
    });
  }

  setAddonQuantity() {
    this.$optionQuanity.change((e) => {
      let addonIndex = this.addons.findIndex(x => x.url === e.target.dataset.url)
      if (addonIndex != -1) {
        this.addons[addonIndex].quantity = parseFloat(e.target.value)
      } else {
        this.addons.push({
          url: e.target.dataset.url,
          price: parseFloat(e.target.dataset.price),
          quantity: parseFloat(e.target.value)
        });
      }
      this.updatePrice()
    });
  }

  updatePrice() {
    //class holds no default state for selected varients
    // if the quantity is undif it sets it to 1
    let quantity;
    if (this.currentVarient.quantity === undefined) {
      quantity = 1
    } else {
      quantity = this.currentVarient.quantity
    }

    let totalAddonPrice = 0;
    for (let i = 0; i < this.addons.length; i ++) {
      totalAddonPrice += (this.addons[i].price * this.addons[i].quantity)
    }
    this.price = "$"+String((((this.currentVarient.price * quantity) + totalAddonPrice) / 100).toFixed(2))
    this.$bannerPrice.text(this.price)
  }


  postToCart(data) {

    $.ajax({
        method: "POST",
        url: "/cart/add.js",
        dataType: 'json',
        async: false, // MUST have ASYNC false
        data: data,
        success: function(res) {
          console.log('success', res)
        }
      });
  }

   addToCart() {
    this.$addToCart.click((e) => {
      if (product.options.length > 1) {
        const values = Object.values(this.options)

        if (values.length < 2) {
          return  alert("Please select all options")
        }
      }
      if (!this.cartClicked) {
        this.cartClicked = true

        let variantData = {
          quantity: this.currentVarient.quantity,
          id: this.currentVarient.id
        }
        // adds variant to cart
         this.postToCart(variantData)
         // ads addons to cart (if any)
        for (let i = 0; i < this.addons.length; i++) {
          // get varient id
          $.getJSON(this.addons[i].url + '.js', (product) => {
            let id = product.variants[0].id

            let data = {
              quantity: this.addons[i].quantity,
              id: id
            }
            this.postToCart(data)
          });
        }
        $(e.target).html("<i class='fas fa-check-circle'></i> Added to cart")
        setTimeout( () => {
          window.location.href = "/cart"
        }, 2000)
      }
      });
  }

}

export default Product
