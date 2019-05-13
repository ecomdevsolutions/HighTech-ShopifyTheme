export const getHostName = ( url ) => {
  let match = url.match(/:\/\/(www[0-9]?\.)?(.[^/:]+)/i)
  
  if ( match != null &&
       match.length > 2 &&
       typeof match[2] === 'string' &&
       match[2].length > 0 ) {
    return match[2]
  } else {
    return null
  }
}

export const isExternalLink = ( url ) => {
  let match = url.match(/^([^:\/?#]+:)?(?:\/\/([^\/?#]*))?([^?#]+)?(\?[^#]*)?(#.*)?/)
  
  if ( match != null &&
       typeof match[1] === 'string' &&
       match[1].length > 0 &&
       match[1].toLowerCase() !== location.protocol )
    return true
  
  if ( match != null &&
       typeof match[2] === 'string' &&
       match[2].length > 0 &&
       match[2].replace(new RegExp(':('+{'http:':80,'https:':443}[location.protocol]+')?$'),'') !== location.host ) {
    return true
  } else {
    return false
  }
}

