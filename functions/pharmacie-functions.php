<?php /* Fonctions pour faire fonctionner le thème */


if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'pharmacie-thumbnail', 200, 200, true); // name, width, height, crop
    add_filter('image_size_names_choose', 'my_image_sizes');
}

function my_image_sizes($sizes) {
    $addsizes = array(
        "pharmacie-thumbnail" => __( "Image mise en avant"),
    );
    $newsizes = array_merge($sizes, $addsizes);
    return $newsizes;
}

function change_post_per_page ( $query ) {
    if ( is_admin() || ! $query->is_main_query() )
        return;

    if ( is_home() ) {
        return;
    }

    //Squizz la suite
    $query->set( 'posts_per_page', -1 );
    $query->set( 'orderby', ['name' => 'ASC'] );
    return;

    if ( is_post_type_archive( 'aromatherapie' ) || is_post_type_archive( 'phytotherapie' ) || is_post_type_archive( 'produit' ) ) {
        $query->set( 'posts_per_page', -1 );
        $query->set( 'orderby', ['name' => 'ASC'] );
        return;
    }
}

add_action( 'pre_get_posts', 'change_post_per_page', 1 );

/* Rediriger les pages de base pour archives */
add_filter( 'template_include', 'redirect_archives' );

function redirect_archives ( $template ) {
	if (is_404()) {
		$taxonomies = get_taxonomies([
			'show_in_rest' => true,
			'_builtin' => false
		]);
		global $wp;
		foreach ($taxonomies as $taxonomy) {
			if ($wp->request == $taxonomy) {
				$new_template = locate_template('taxonomy-template.php');
				add_filter('document_title_parts', 'edit_archive_title', 10, 2);
			}
		}
	}

	if ($new_template) return $new_template;
	return $template;
}

function edit_archive_title ( $title ) {
	global $wp;
	$taxonomy_name = $wp->request;
	$taxonomy = get_taxonomy($taxonomy_name);
	$title['title'] = $taxonomy->label;
	return $title;
}

function array_debug ($array) {
  if (is_admin()) return;
  echo '<pre>';
  var_dump($array);
  echo '</pre>';
}

function slug ($z) {
    $z = strtolower($z);
    $z = preg_replace('/[^a-z0-9 _]+/', '_', $z);
    $z = str_replace(' ', '_', $z);
    return trim($z, '');
}

function get_icon_and_class ( $type, $status ) {
	if ($type == 'precaution') {
		switch ($status) {
			case '0':
				return (object) [
					'class' => 'secondary',
					'icon' => 'question-circle'
				];
				break;
			case '1':
				return (object) [
					'class' => 'warning',
					'icon' => 'exclamation-triangle'
				];
				break;
			case '2':
				return (object) [
					'class' => 'danger',
					'icon' => 'times-circle'
				];
				break;
			default:
				break;
		}
	}
	if ($type == 'utilisation') {
		switch ($status) {
			case '-1':
				return (object) [
					'class' => 'danger',
					'icon' => 'times-circle'
				];
				break;
			case '0':
				return (object) [
					'class' => 'secondary',
					'icon' => 'question-circle'
				];
				break;
			case '1':
				return (object) [
					'class' => 'level-1',
					'icon' => 'check-circle'
				];
				break;
			case '2':
				return (object) [
					'class' => 'level-2',
					'icon' => 'check-circle'
				];
				break;
			case '3':
				return (object) [
					'class' => 'level-3',
					'icon' => 'check-circle'
				];
				break;
			default:
				break;
		}
	}
}

function get_post_by_url ($url) {
    $path = explode('/', wp_parse_url(untrailingslashit($url))['path']);
    if ($post = get_page_by_path($path[2], OBJECT, $path[1])) {
      return $post;
    } else {
      return (Object) [
        'post_title' => $url
      ];
    }
}

function links_to_popover ($content) {
  $dom = new Pharma_DOMDocument();
  $dom->loadHTML($content);
  foreach ($dom->getElementsByTagName('a') as $item) {
    $post = get_post_by_url($item->getAttribute('href'));
    $item->setAttribute('data-toggle', 'popover');
    $item->setAttribute('data-placement', 'bottom');
    $item->setAttribute('data-trigger', 'hover');
    $item->setAttribute('data-html', 'true');
    $item->setAttribute('data-id', $post->ID);
    $item->setAttribute('data-slug', $post->post_name);
    global $popover_array;
    $popover_array[$post->post_name] = [
      'title' => $post->post_title,
      'content' => '<div class="media">'.get_the_post_thumbnail($post->ID, [68, 68], ['class' => 'border rounded float-left mr-2']).'<div class="media-body">'.lazyblock_identite_callback ('', parse_blocks($post->post_content), $archive = true).'</div></div>'
    ];
  }

  foreach ($dom->getElementsByTagName('p') as $item) {
    $item->setAttribute('class', 'mb-0');
  }

  return html_entity_decode($dom->saveHTML());
}

add_action('wp_footer', function () {
  global $popover_array;
  echo '<script>
    var popover = '.json_encode($popover_array).';
  </script>';
});


//Première image du post comme immage mise en avant
function auto_featured_image() {
    global $post;

    if (!has_post_thumbnail($post->ID)) {
        $attached_image = get_children( "post_parent=$post->ID&amp;post_type=attachment&amp;post_mime_type=image&amp;numberposts=1" );

      if ($attached_image) {
              foreach ($attached_image as $attachment_id => $attachment) {
                   set_post_thumbnail($post->ID, $attachment_id);
              }
         }
    }
}
// Use it temporary to generate all featured images
add_action('the_post', 'auto_featured_image');
// Used for new posts
add_action('save_post', 'auto_featured_image');
add_action('draft_to_publish', 'auto_featured_image');
add_action('new_to_publish', 'auto_featured_image');
add_action('pending_to_publish', 'auto_featured_image');
add_action('future_to_publish', 'auto_featured_image');
