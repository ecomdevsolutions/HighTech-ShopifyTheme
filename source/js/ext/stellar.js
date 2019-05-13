import 'jquery-stellar'

export const initStellar = () => {
  $.stellar('destroy')
  $.stellar({
    horizontalScrolling: false,
    hideDistantElements: false    
  })
}
