class Product {

  constructor () {
    this.$window = $(window)
    this.$body = $('body')
    this.$form = $('.shopify-product-form')
    this.$images = $('.product__images')
    this.$thumb = $('.product__thumb')
    this.$banner = $('.product-banner')


    this.$variantSelect = this.$form.find('select[name="id"]')
    this.$optionValue = $('.product__option__value')


    this.$varients = $('.tablinks--varients')
    this.$bannerPrice = $('.product-banner__price')
    this.$addons = $('.button--addon')
    this.$addToCart = $(".button--add-to-cart")
    this.currentVarient = null
    this.price = null
    this.addon_price = 0
    this.addons = []
    this.cartClicked = false
    this.addonHTML = {}
    //passes in global var product.liquid: line:213

  }

  static get bodyClass () {
    return 'shop-product'
  }


  init () {
    this.initImages()
    this.filterImages(this.getSelectedVariant())

    this.currentVarient = CURRENT_VARIENT_PRICE




    this.addToCart()
    this.varientSelect()
    this.addonSelect()

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
    let variant = this.getSelectedVariant()
    this.$bannerPrice.html(Shopify.formatMoney(variant.price).replace(/(\..*)/, ''))
    this.$variantSelect.val(variant.id)
    this.filterImages(variant)
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

  varientSelect() {
    this.$varients.click((e) => {
      this.currentVarient = {id: e.target.dataset.id, price: parseFloat(e.target.dataset.price)}
      this.updatePrice()
    });
  }

  updatePrice() {
    this.price = "$"+String(((this.currentVarient.price + this.addon_price) / 100).toFixed(2))
    this.$bannerPrice.text(this.price)
  }
  addonSelect() {

    this.$addons.click((e) => {
      //Deselect addon
      if (this.addons.includes(e.target.dataset.url)) {
        //get position of {{product.url}} in array
        let pos = this.addons.indexOf(e.target.dataset.url)
        //remove it
        this.addons.splice(pos,1)
        //subtract price
        this.addon_price -= parseFloat(e.target.dataset.price)
        //update price
        this.updatePrice()
          //set html in object ref'ed by product ID
        $("#"+e.target.dataset.id).html(this.addonHTML[e.target.dataset.id])
        $("#"+e.target.dataset.id).css("background-color", "#fff")
        $("#"+e.target.dataset.id).css("color", "#000")
      } else {
        //Select addon
        this.addons.push(e.target.dataset.url)
        //Add price
        this.addon_price += parseFloat(e.target.dataset.price)
        //Update price
        this.updatePrice()
        $("#"+e.target.dataset.id).css("background-color", "#0074E4")
        $("#"+e.target.dataset.id).css("color", "#fff")
        //store html in object ref'ed by product ID
        this.addonHTML[e.target.dataset.id] = $("#"+e.target.dataset.id).html()
        //update html to clicked state
      $("#"+e.target.dataset.id).html(`<i data-id='${e.target.dataset.id}' data-pirce=${e.target.dataset.price} class='fas fa-check-circle'></i> Added to order`)
      }
    })
  }

  postToCart(data) {
    $.ajax({
        method: "POST",
        url: "/cart/add.js",
        dataType: 'json',
        async: false,
        data: data,
        success: function(res) {
          console.log('success', res)
        }
      })
  }

   addToCart() {

    this.$addToCart.click((e) => {
      if (!this.cartClicked) {
        console.log(this)
        this.cartClicked = true
        let data = {
          quantity: 1,
          id: this.currentVarient.id
        }
         this.postToCart(data)

        for (let i = 0; i < this.addons.length; i++) {
          $.getJSON(this.addons[i] + '.js', (product) => {
            let id = product.variants[0].id
            let data = {
              quantity: 1,
              id: id
            }

            this.postToCart(data)
          })
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
