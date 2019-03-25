<?php

class PharmaLoop {

  public $has_blocks = false;

  public $content = "";
  public $blocks = array();
  protected $blocks_map = array();
  protected $dom;
  protected $id;

  public function __construct ($content) {
    $this->content = $content;
    $this->id = get_the_ID();
    $this->dom = new Pharma_DOMDocument();
    $this->dom->formatOutput = true;
  }

  public function get_the_subtitle() {
    return null;
  }

  public function get_article () {
    $article = $this->dom->createChildElement($this->dom, 'article', [
      'role' => 'article',
      'id' => 'post_'.$this->id,
      'class' => join(' ', get_post_class())
    ]);

    $header = $this->get_header();
    $article->appendChild($header);

    $main = $this->get_main();
    $article->appendChild($main);

    $footer = $this->get_footer();
    $article->appendChild($footer);

    //array_debug($this->blocks);

    return html_entity_decode($this->dom->saveHTML());
  }

  protected function get_header () {
    $header = $this->dom->createElement('header', [
      'class' => 'mb-4'
    ]);
    if (get_post_type() == 'post' && has_post_thumbnail()) {
      $card = $this->dom->createChildElement($header, 'div', ['class' => 'card pharmacie-header text-white'], get_the_post_thumbnail(null, 'post-thumbnail', ['class' => 'card-img']));
      $overlay = $this->dom->createChildElement($card, 'div', ['class' => 'card-img-overlay d-flex justify-content-center align-items-center']);
      $dark = $this->dom->createChildElement($overlay, 'div', ['class' => 'position-absolute w-100 h-100', 'style' => 'background-color: black; opacity: .4;']);
      $this->dom->createChildElement($overlay, 'h1', ['class' => 'card-title', 'style' => 'z-index: 1;'], get_the_title());
    } else {
      $title = $this->dom->createChildElement($header, 'h1', [], get_the_title());
    }
    if ($the_subtitle = $this->get_the_subtitle()) {
      $subtitle = $this->dom->createChildElement($header, 'h2', ['class' => 'text-muted font-italic font-weight-light'], $the_subtitle);
    }
    return $header;
  }

  protected function get_main () {
    $content = $this->get_the_content($this->content);
    $main = $this->dom->createElement('main', ['class' => 'mt-4'], $content);
    $row = $this->dom->createChildElement($main, 'div', ['class' => 'row'], wp_link_pages(['echo' => false]));
    return $main;
  }

  protected function get_the_content($content) {
    $content = apply_filters('the_content', $this->content);
    $content = str_replace(']]>', ']]&gt;', $content);
    return $content;
  }

  protected function get_footer () {
    $footer = $this->dom->createElement('footer', ['class' => 'mt-5 border-top pt-3']);

    $post_date = get_the_date();
    $post_date_modified = get_the_modified_date();
    $post_type = get_post_type_object( get_post_type() );
    $header_meta_value = $post_type->labels->singular_name . " " . translate('créé•e par', 'b4st'). " " . get_the_author_posts_link() . translate(' on ', 'b4st') . $post_date;
    if ($post_date != $post_date_modified) {
      $header_meta_value .= " (" . translate('dernière mise à jour le') . " " . $post_date_modified .")";
    }
    $header_meta = $this->dom->createChildElement($footer, 'div', [
      'class' => 'header-meta text-muted'
    ], $header_meta_value);

    $this->dom->createChildElement($footer, 'hr');

    $footer_meta_value = "";
    if (has_category()) {
      $footer_meta_value .= translate('Catégorie•s', 'b4st') . " : " . get_the_category_list(', ') . ' | ';
    }
    if (has_tag()) {
      $footer_meta_value .= get_the_tags(translate('Catégorie•s', 'b4st') . " : ", ', ') . ' | ';
    }
    $footer_meta_value .= get_comments_number_text( translate('Pas de commentaire', 'b4st'), translate('Un commentaire', 'b4st'), translate('% commentaires', 'b4st') );
    $footer_meta = $this->dom->createChildElement($footer, 'p', [], $footer_meta_value);
    $author_bio = $this->dom->createChildElement($footer, 'div', ['class' => 'author-bio media border-top pt-3'], get_avatar('', $size = '96'));
    $author_bio_body = $this->dom->createChildElement($author_bio, 'div', ['class' => 'media-body ml-3']);
    $author_name = $this->dom->createChildElement($author_bio_body, 'p', ['class' => 'h4 author-name'], get_the_author_posts_link());
    $author_description = $this->dom->createChildElement($author_bio_body, 'p', ['class' => 'author-description'], get_the_author_meta('description'));
    $author_other_posts = $this->dom->createChildElement($author_bio_body, 'p', ['class' => 'author-other-posts mb-0 border-top pt-3'], translate('Other posts by ', 'b4st') . get_the_author_posts_link());
    return $footer;
  }

}
