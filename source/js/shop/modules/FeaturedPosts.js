import moment from 'moment'

class FeaturedPosts {
  constructor ( $el ) {
    this.$el = $el

    this.onAjaxSuccess = this.onAjaxSuccess.bind(this)
    this.onAjaxError = this.onAjaxError.bind(this)
  }

  init () {
    if ( !this.$el.length ) return

    let url = this.$el.find('[data-blog-url]').data('blog-url')

    $.ajax({
      url: `${url}?json=get_recent_posts&count=2`,
      dataType: 'jsonp',      
      cache: false,
      success: this.onAjaxSuccess,
      error: this.onAjaxError
    })
  }

  destroy () { 

  }

  renderPost ( data ) {
    let image = data.thumbnail_images.large,
        percentage = (image.height / image.width) * 100,
        date = moment(data.date).format('MMMM Do, YYYY')
    
    return `
    <div class="col-mb-lg-6">
      <article class="post post--index">
        <div class="box" style="padding-top: ${percentage}%;">
          <div class="box__inner">
            <div class="image image--cover image--load--fadein">
              <a href="${data.url}">
                <img class="lazyload" 
                     data-src="${image.url}">
              </a>
            </div>
          </div>
        </div>
        <div class="post__meta">
          <time class="h6--eyebrow">${date}</time>
          <h2 class="h5"><a href="${data.url}">${data.title}</a></h2>
          ${data.excerpt}
        </div>
      </article>    
    </div>
    `
  }

  onAjaxSuccess ( data ) {
    let html = data.posts.map(this.renderPost)

    this.$el.find('.row--posts').html(html)
  }
  
  onAjaxError () {

  }
}

export default FeaturedPosts
