<?php

class PharmaLoop_produit extends PharmaLoop {

  public function __construct ($content) {
    echo '<!-- PharmaLoop_produit -->';
    parent::__construct($content);
  }

  public function get_the_subtitle () {
    $laboratoires = [];
    foreach (get_the_terms($this->id, "laboratoire") as $laboratoire) {
      $laboratoires[] = $laboratoire->name;
    }
    return implode($laboratoires, ", ");
  }

  protected function get_main () {
    $main = $this->dom->createElement('main');
    $row = $this->dom->createChildElement($main, 'div', ['class' => 'row'], wp_link_pages(['echo' => false]));
    $col1 = $this->dom->createChildElement($row, 'div', ['class' => 'col-md-4 d-flex align-items-start'], get_the_post_thumbnail(null, 'large', ['class' => 'align-middle mx-auto d-block']));
    $col2 = $this->dom->createChildElement($row, 'div', ['class' => 'col-md-8'], $this->get_the_content($this->content));
    return $main;
  }

}
