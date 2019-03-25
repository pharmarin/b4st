<?php

class Pharma_DOMDocument extends DOMDocument {

  function __construct($version = null, $encoding = null) {
    parent::__construct($version, 'UTF-8');
    $this->formatOutput = true;
  }

  function loadHTML ($source, $options = 0) {
    parent::loadHTML(mb_convert_encoding($source, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
  }

  function createElement($balise, $attributes = [], $node_value = "") {
    $element = parent::createElement($balise);
    foreach ($attributes as $key => $value) {
      $element->setAttribute($key, $value);
    }
    $element->nodeValue = $node_value;
    return $element;
  }

  function createChildElement($parent, $balise, $attributes = [], $node_value = "") {
    $element = $this->createElement($balise, $attributes, $node_value);
    //array_debug($element);
    $parent->appendChild($element);
    return $element;
  }

}
