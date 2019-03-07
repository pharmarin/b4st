<?php
/**!
* Enqueues
*/

if ( ! function_exists('b4st_enqueues') ) {
	function b4st_enqueues() {

		// Styles

		wp_register_style('bootstrap-css', get_stylesheet_directory_uri().'/theme/css/bootstrap.min.css', false, '4.1.3', null);
		wp_enqueue_style('bootstrap-css');

		wp_register_style('fontawesome5-css', 'https://use.fontawesome.com/releases/v5.4.1/css/all.css', false, '5.4.1', null);
		wp_enqueue_style('fontawesome5-css');

		// Scripts

		wp_register_script('modernizr',  'https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js', false, '2.8.3', true);
		wp_enqueue_script('modernizr');

		//La fonction supprime l'utilisation du fichier original de JQuery sur votre serveur
		wp_deregister_script( 'jquery' );
		//Elle enregistre alors le nouvel emplacement de JQuery, chargé depuis le CDN de Google
		wp_register_script( 'jquery', ( 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js' ), false, null, true );
		//La fonction charge JQuery
		wp_enqueue_script( 'jquery' );

		wp_register_script('popper',  'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js', false, '1.14.3', true);
		wp_enqueue_script('popper');

		wp_register_script('bootstrap-js', get_stylesheet_directory_uri() . '/theme/js/bootstrap.min.js', false, '4.1.3', true);
		wp_enqueue_script('bootstrap-js');

		wp_register_script( 'rwdImageMaps', 'https://cdnjs.cloudflare.com/ajax/libs/jQuery-rwdImageMaps/1.6/jquery.rwdImageMaps.min.js', false, false, true );
		wp_enqueue_script('rwdImageMaps');

		wp_register_script( 'custom-scripts', get_stylesheet_directory_uri() . '/theme/js/custom-scripts.js', false, false, true );
		wp_enqueue_script('custom-scripts');

		wp_register_script('react', 'https://unpkg.com/react/umd/react.production.min.js', false, false, false);
		wp_enqueue_script('react');

		wp_register_script('react-dom', 'https://unpkg.com/react-dom/umd/react-dom.production.min.js', false, false, false);
		wp_enqueue_script('react-dom');

		wp_register_script( 'react-app', get_stylesheet_directory_uri() . '/theme/js/react-app.js', false, false, true );
		wp_enqueue_script('react-app');

		if (is_front_page()) {
			wp_register_script( 'front-page', get_stylesheet_directory_uri() . '/theme/js/front-page.js', false, false, true );
			wp_enqueue_script('front-page');
		}

		if (is_singular() && comments_open() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply');
		}
	}
}
add_action('wp_enqueue_scripts', 'b4st_enqueues', 100);
