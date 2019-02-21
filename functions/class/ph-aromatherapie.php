<?php

class PharmaLoop_aromatherapie extends PharmaLoop {

  public function __construct ($content) {
    echo '<!-- PharmaLoop_aromatherapie -->';
    parent::__construct($content);
  }

  public function get_the_subtitle() {
    if (has_blocks($this->content)) {
      $blocks = parse_blocks($this->content);
      foreach ($blocks as $block) {
        if ($block['blockName'] == "lazyblock/identite") {
          return $block['attrs']['nom_latin'];
          break;
        }
      }
    }
  }

  protected function get_header () {
    $header = $this->dom->createElement('header', [
      'class' => 'mb-4'
    ]);
    $row = $this->dom->createChildElement($header, 'div', ['class' => 'row']);
    if ($the_thumbnail = get_the_post_thumbnail(get_the_ID(), '', ['class' => 'img-thumbnail img-fluid'])) {
      $col_title = $this->dom->createChildElement($row, 'div', ['class' => 'col-9 col-md-10']);
      $col_thumbnail = $this->dom->createChildElement($row, 'div', ['class' => 'float-right col-3 col-md-2'], $the_thumbnail);
    } else {
      $col_title = $this->dom->createChildElement($row, 'div', ['class' => 'col-12']);
    }
    $title = $this->dom->createChildElement($col_title, 'h1', [], get_the_title());
    if ($the_subtitle = $this->get_the_subtitle()) {
      $subtitle = $this->dom->createChildElement($col_title, 'h2', ['class' => 'text-muted font-italic font-weight-light'], $the_subtitle);
    }
    return $header;
  }

  protected function get_main () {
    $content = $this->get_the_content($this->content);
    $main = $this->dom->createElement('main', [], $content);
    return $main;
  }

}
