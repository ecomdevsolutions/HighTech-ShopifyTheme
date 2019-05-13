import Modernizr from 'modernizr'

export const Breakpoints = {
  MOBILE_LARGE: 568,
  TABLET: 768,
  TABLET_LARGE: 900,
  DESKTOP: 1024,
  LARGE: 1280
}

export const inIframe = () => {
  try {
    return window.self !== window.top
  } catch (e) {
    return true
  }
}

export const isMobileLarge = () => {
  return Modernizr.mq('(min-width: ' + Breakpoints.MOBILE_LARGE + 'px)')
}

export const isTablet = () => {
  return Modernizr.mq('(min-width: ' + Breakpoints.TABLET + 'px)')
}

export const isTabletLarge = () => {
  return Modernizr.mq('(min-width: ' + Breakpoints.TABLET_LARGE + 'px)')
}

export const isDesktop = () => {
  return Modernizr.mq('(min-width: ' + Breakpoints.DESKTOP + 'px)')
}
