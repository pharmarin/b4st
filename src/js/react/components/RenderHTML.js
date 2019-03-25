import React from 'react';

export function RenderHTML (rawHTML) {
  var escapeChars = { lt: '<', gt: '>', quot: '"', apos: "'", amp: '&' };
  if (!rawHTML) return null;
  var processedHTML = rawHTML.replace(/\&([^;]+);/g, function(entity, entityCode) {
    var match;

    if ( entityCode in escapeChars) {
      return escapeChars[entityCode];
    } else if ( match = entityCode.match(/^#x([\da-fA-F]+)$/)) {
      return String.fromCharCode(parseInt(match[1], 16));
    } else if ( match = entityCode.match(/^#(\d+)$/)) {
      return String.fromCharCode(~~match[1]);
    } else {
      return entity;
    }
  });
  const regex = /(<([^>]+)>)/ig;
  return processedHTML.replace(regex, '');
}
