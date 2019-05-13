class Article {
  constructor () {
    this.$articlePages = $('.article__pages')
  }

  static get bodyClass () {
    return 'docs-article'
  }

  init () {
    this.setActivePage()
  }

  destroy () {

  }

  setActivePage () {
    let pathname = window.location.pathname.replace(/\/$/g, '')

    this.$articlePages.find('li').each((k,v) => {
      let $this = $(v),
          href = $this.find('a').attr('href')

      if ( href == pathname ) {
        $this.addClass('active')
      }
    })    
  }  
}

export default Article
