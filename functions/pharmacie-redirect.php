<?php



add_filter( 'query_vars', 'add_query_vars_filter' );

function add_query_vars_filter( $vars ) {
  $vars[] = "taxonomy";
  return $vars;
}

add_action( 'generate_rewrite_rules', 'taxonomy_archive_permalink' );

function taxonomy_archive_permalink( $wp_rewrite ) {
	$args = array(
	  'public'   => true,
	  '_builtin' => false
	);
	$output = 'objects'; // or names
	$operator = 'and'; // 'and' or 'or'
	$taxonomies = get_taxonomies( $args, $output, $operator );
	$index = 'taxonomy-template.php'; // $wp_rewrite->index
	//$taxonomies = get_taxonomies();
	//var_dump($wp_rewrite);
	if ( $taxonomies ) {
		foreach ( $taxonomies  as $taxname => $taxonomy ) {
			$slug = $taxonomy->rewrite['slug'];
			unset($wp_rewrite->rules[$slug.'(/[0-9]+)?/?$']);
			$wp_rewrite->rules = array(
				//$slug.'(/[0-9]+)?/?$' => $wp_rewrite->index . '?'.$taxonomy->query_var.'&paged=' . $wp_rewrite->preg_index( 1 ),
				$slug.'/?$' => $index . '?taxonomy='.$taxonomy->query_var.'&paged=' . $wp_rewrite->preg_index( 1 ),
				$slug.'/page/?([0-9]{1,})/?$' => $index . '?taxonomy='.$taxonomy->query_var.'&paged=' . $wp_rewrite->preg_index( 1 ),
			) + $wp_rewrite->rules;
		}
	}
}

add_action( 'parse_request', 'parse_archive_request' );

function parse_archive_request( &$wp ) {
  if ( array_key_exists( 'taxonomy', $wp->query_vars ) && $filepath = locate_template('taxonomy-template.php')) {
		set_query_var('taxonomy', $wp->query_vars['taxonomy'] );
    load_template($filepath);
		exit;
  }
}

add_filter( 'wp_title_parts', 'change_archive_title', 10, 2 );

function change_archive_title ($title_parts) {
	if ($slug = get_query_var('taxonomy')) {
		$taxonomy = get_taxonomy($slug);

		$new_parts = $title_parts;
		$new_parts['title'] = $taxonomy->labels->name;

		return $new_parts;
	}
	return $title_parts;
}
