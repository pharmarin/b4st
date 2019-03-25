<?php //Rest api modifier

add_action( 'rest_api_init', function () {
  register_rest_field( ['aromatherapie', 'phytotherapie'], 'blocks', array(
    'get_callback' => function( $attributes ) {
      $content = $attributes["content"]["raw"];

      if (has_blocks($content)) {
        $content = parse_blocks($content);
        $content_array = [];
        $unique_blocks = ["lazyblock/identite", "lazyblock/precautions", "lazyblock/utilisation-he"];
        $check_array = '%5B%7B%22';
        $check_array_alt = '%7B%22';
        foreach ($content as $block) {
          if ($block["blockName"] == "") {
            continue;
          } elseif (in_array($block["blockName"], $unique_blocks)) {
            $block_item = $block;
            foreach ($block["attrs"] as $key => $attr) {
              if ( substr( $attr, 0, strlen( $check_array ) ) === $check_array || substr( $attr, 0, strlen( $check_array_alt ) ) === $check_array_alt ) {
                $block_item["attrs"][$key] = json_decode( urldecode( $attr ), true );
              }
            }
            $content_array[$block["blockName"]] = $block_item;
          } else {
            $block_item = $block;
            foreach ($block["attrs"] as $key => $attr) {
              if ( substr( $attr, 0, strlen( $check_array ) ) === $check_array || substr( $attr, 0, strlen( $check_array_alt ) ) === $check_array_alt ) {
                $block_item["attrs"][$key] = json_decode( urldecode( $attr ), true );
              }
            }
            $content_array[$block["blockName"]][] = $block_item;
          }
        }
      } //has_blocks

      return $content_array;
    },
    'update_callback' => function( $karma, $comment_obj ) {
      return;
    },
  ));

  register_rest_field( ['aromatherapie', 'phytotherapie', 'produit'], 'terms', array(
    'get_callback' => function( $attributes ) {
      $content = $attributes["content"]["raw"];

      $terms = [];

      foreach (get_object_taxonomies( get_post_type(), 'objects' ) as $term) {
        $terms[$term->name] = get_the_terms(get_the_ID(), $term->name);
      }

      return $terms;
    },
    'update_callback' => function( $karma, $comment_obj ) {
      return;
    },
  ));

});
