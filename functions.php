<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$includes = array(
	'/setup.php',
	'/hooks.php',
	'/enqueues.php',
	'/index-pagination.php',
	'/pharmacie-class.php',
	'/pharmacie-enqueue-blocks.php',
	'/pharmacie-render-blocks.php',
	'/pharmacie-functions.php',
	'/pharmacie-rest-api.php',
	'/languages.php'
);

foreach ( $includes as $file ) {
	$filepath = locate_template( 'functions' . $file );
	if ( ! $filepath ) {
		trigger_error( sprintf( 'Error locating functions%s for inclusion', $file ), E_USER_ERROR );
	}
	require_once $filepath;
}
